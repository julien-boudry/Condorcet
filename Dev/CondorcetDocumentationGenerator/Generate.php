<?php declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator;

use HaydenPierce\ClassFinder\ClassFinder;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\{ConstExprParser, PhpDocParser, TokenIterator, TypeParser};
use PHPStan\PhpDocParser\Ast\PhpDoc\{PhpDocNode, PhpDocTextNode};
use PHPStan\PhpDocParser\ParserConfig;
use Reflection;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

use function Laravel\Prompts\{info, note, warning};

class Generate
{
    public const DOCS_URL = 'https://docs.condorcet.io';
    public const GITHUB_BASE = 'https://github.com/julien-boudry/Condorcet';
    public const GITHUB_BRANCH_PATH = '/blob/master/';

    // Static - Translators

    public static function makeFilename(ReflectionMethod|ReflectionProperty $page): string
    {
        return str_replace('\\', '_', self::simpleClass($page->class)) . '--' . $page->name .
                '.md';
    }

    public static function simpleClass(string $fullClassName): string
    {
        return str_replace('CondorcetPHP\\Condorcet\\', '', $fullClassName);
    }

    public static function speakBool($c): string
    {
        if ($c === true || $c === 'true') {
            return 'true';
        }
        if ($c === false || $c === 'false') {
            return 'false';
        }
        if ($c === null || $c === 'null') {
            return 'null';
        }
        if (\is_array($c)) {
            return '[' . implode(',', $c) . ']';
        }
        if (\is_object($c)) {
            return 'new ' . $c::class;
        }

        return (string) $c;
    }

    public static function getTypeAsString(?\ReflectionType $rf_rt, bool $codeBlock = false): ?string
    {
        if ($rf_rt !== null) {
            if ($codeBlock) {
                return '`' . ($rf_rt) . '`';
            } else {
                return (string) $rf_rt;
            }
        }

        return $rf_rt;
    }

    public static function getGithubLink(\ReflectionFunctionAbstract|ReflectionClass $refl): string
    {
        return self::GITHUB_BASE . self::GITHUB_BRANCH_PATH .
                substr($refl->getFileName(), mb_strpos($refl->getFileName(), '/src/') + 1) .
                '#L' . $refl->getStartLine()
        ;
    }

    public static function getModifiersName(ReflectionMethod|ReflectionProperty $reflection): string
    {
        return implode(' ', Reflection::getModifierNames($reflection->getModifiers()));
    }

    // Static - Builder

    public function cleverRelated(string $name): string
    {
        [$class, $pointer] = explode('::', $name);

        if (!isset($this->fullPagesListMeta[$class]['page'][$pointer])) {
            warning('Cannot create link to page:' . $pointer . ' on class:' . $class . ' as input name: ' . $name);
            return '[' . $name . ']()';
        }

        $reflector = $this->fullPagesListMeta[$class]['page'][$pointer]['Reflection'];

        $url = $this->getUrl($reflector);

        return '[' . $name . '](' . $url . ')';
    }

    public static function computeRepresentationAsForIndex(ReflectionMethod|ReflectionProperty $reflection): array
    {
        if ($reflection instanceof ReflectionMethod) {
            $parameters = ' (' . (($reflection->getNumberOfParameters() > 0) ? '...' : '') . ')';
        } else {
            $parameters = '';
        }

        return [self::getModifiersName($reflection),
                self::simpleClass($reflection->class) .
                (($reflection->isStatic()) ? '::' : '->') .
                $reflection->name .
                $parameters
        ];
    }

    public static function computeRepresentationAsPHP(ReflectionMethod|ReflectionProperty $reflection): string
    {
        $str = '';
        $str = $reflection instanceof ReflectionMethod ? '(' : '';
        $i = 0;


        if ($reflection instanceof ReflectionMethod && $reflection->getNumberOfParameters() > 0) {
            $option = false;

            foreach ($reflection->getParameters() as $value) {
                $str .= ' ';
                $str .= ($value->isOptional() && !$option) ? '[' : '';
                $str .= ($i > 0) ? ', ' : '';
                $str .= self::getTypeAsString($value->getType());
                $str .= ' ';
                $str .= $value->isPassedByReference() ? '&' : '';
                $str .= '$' . $value->getName();
                $str .= $value->isDefaultValueAvailable() ? ' = ' . self::speakBool($value->getDefaultValue()) : '';

                ($value->isOptional() && !$option) ? $option = true : null;
                $i++;
            }

            if ($option) {
                $str .= ']';
            }
        }

        $r = "```php\n";

        if ($reflection instanceof ReflectionMethod) {
            $str .= ' )';

            $returnType = $reflection->getReturnType();

            $r .=   self::getModifiersName($reflection) .
                    ' ' .
                    self::simpleClass($reflection->class) .
                    ($reflection->isStatic() ? '::' : '->') .
                    $reflection->name .
                    ' ' .
                    $str .
                    (self::getTypeAsString($returnType) !== null ? ': ' . $returnType : '')
            ;
        } elseif ($reflection instanceof ReflectionProperty) {
            $type = $reflection->getType();

            $r .=   self::getModifiersName($reflection) .
                    ' ' .
                    (self::getTypeAsString($type) !== null ? $type . ' ' : '') .
                    self::simpleClass($reflection->class) .
                    ($reflection->isStatic() ? '::' : '->') .
                    $reflection->name .
                    ' ' .
                    $str
            ;
        } else {
            throw new \Error('Unknown type');
        }

        $r .= "\n```";

        return $r;
    }


    // Script

    protected PhpDocParser $phpDocParser;
    protected Lexer $lexer;
    protected array $fullPagesListMeta = [];

    public function __construct($path, public readonly string $pathBase)
    {
        $start_time = microtime(true);

        // basic setup

        $config = new ParserConfig(usedAttributes: []);
        $this->lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $this->phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

        $pathDirectory = $path . \DIRECTORY_SEPARATOR;

        //
        $FullClassList = ClassFinder::getClassesInNamespace('CondorcetPHP\Condorcet\\', ClassFinder::RECURSIVE_MODE);
        $FullClassList = array_filter($FullClassList, static fn(string $value): bool => !str_contains($value, 'Condorcet\Test') && !str_contains($value, 'Condorcet\Dev') && !str_contains($value, 'Condorcet\Benchmarks'));

        $inDoc = 0;
        $non_inDoc = 0;
        $total_pages = 0;
        $total_nonInternal = 0;

        // populate fullclassMeta
        foreach ($FullClassList as $FullClass) {
            $shortClass = str_replace('CondorcetPHP\Condorcet\\', '', $FullClass);

            $reflectionClass = new ReflectionClass($FullClass);
            $pagesList = array_merge($reflectionClass->getProperties(), $reflectionClass->getMethods());

            $this->fullPagesListMeta[$shortClass]['ReflectionClass'] = $reflectionClass;
            $this->fullPagesListMeta[$shortClass]['pagesList'] = $pagesList;

            foreach ($pagesList as $onePage) {
                $this->fullPagesListMeta[$shortClass]['page'][$onePage->name] = [
                    'name' => $onePage->name,
                    'static' => $onePage->isStatic(),
                    'visibility_public' => $onePage->isPublic(),
                    'visibility_protected' => $onePage->isProtected(),
                    'visibility_private' => $onePage->isPrivate(),
                    'Reflection' => $onePage,
                ];
            }
        }

        // generate .md
        foreach ($FullClassList as $FullClass) {
            $reflectionClass = $this->fullPagesListMeta[$shortClass]['ReflectionClass'];
            $pagesList = $this->fullPagesListMeta[$shortClass]['pagesList'];

            $this->checkQuality($pagesList, $reflectionClass, $inDoc, $non_inDoc);

            $classIsInternal = $this->hasDocBlockTag('@internal', $reflectionClass);

            $shortClass = str_replace('CondorcetPHP\Condorcet\\', '', $FullClass);

            foreach ($pagesList as $onePage) {
                $isPublicApi = $this->hasDocBlockTag('@api', $onePage);

                $total_pages++;

                if ($onePage instanceof ReflectionMethod && !$onePage->isInternal()) {
                    $total_nonInternal++;
                }

                // Write Markdown
                if ($isPublicApi) {
                    if (
                        !$classIsInternal &&
                        (
                            empty($this->getDocBlockTagDescriptionOrValue('@api', $onePage)) ||
                            str_contains($this->getDocBlockTagDescriptionOrValue('@api', $onePage), self::simpleClass($onePage->class))
                        )
                    ) {
                        $path = $pathDirectory . str_replace('\\', '_', self::simpleClass($onePage->class)) . ' Class/';

                        if (!is_dir($path)) {
                            mkdir($path);
                        }

                        file_put_contents($path . self::makeFilename($onePage), $this->createMarkdownContent($onePage));
                    }
                }
            }
        }


        print 'Public Methods/Properties in doc: ' . $inDoc . ' / ' . ($inDoc + $non_inDoc) . ' | Total non-internal Methods/Properties count: ' . $total_nonInternal . ' | Number of Class: ' . \count($FullClassList) . ' | Number of Methods/Properties including internals: ' . $total_pages . "\n";

        // Add Index
        $file_content =  '> **[Presentation](../README.md) | [Documentation Book](' . self::DOCS_URL . ") | API References | [Voting Methods](/Docs/VotingMethods.md) | [Tests](../../tests)**\n\n" .

                        "# API References\n" .
                        "## Public API Index *\n" .

                        '_*: I try to update and complete the documentation. See also [the documentation book](' . self::DOCS_URL . "), [the tests](../tests) also produce many examples. And create issues for questions or fixing documentation!_\n\n";


        $file_content .= $this->makeIndex($this->fullPagesListMeta);

        $file_content .= "\n\n\n";

        uksort($this->fullPagesListMeta, 'strnatcmp');
        $file_content .=    "## Full Class & API Reference\n" .
                            "_Including above methods from public API_\n\n";

        $file_content .= $this->makeProfundis($this->fullPagesListMeta);


        // Write file
        file_put_contents($pathDirectory . 'README.md', $file_content);


        echo 'YAH ! <br>' . (microtime(true) - $start_time) . 's';
    }

    public function checkQuality(
        /** @var array<ReflectionProperty|ReflectionMethod> */
        array $pagesList,
        ReflectionClass $reflectionClass,
        int &$inDoc,
        int &$non_inDoc
    ): void {
        foreach ($pagesList as $onePage) {

            $phpDocNode = false;

            $isPublicApi = $this->hasDocBlockTag('@api', $onePage);

            if (!$onePage->isPublic() && $isPublicApi) {
                warning('Has Public API tag but is not public visibility: ' . $reflectionClass->getName() . '->' . $onePage->getName());
            } elseif ($onePage instanceof ReflectionMethod && $onePage->isInternal()) {
                // continue
            } elseif ($onePage->isPublic() && $isPublicApi) {
                $inDoc++;

                if ($onePage instanceof ReflectionMethod && $onePage->getNumberOfParameters() > 0) {
                    $docBlocParams = $this->getDocBlockParameters($onePage);

                    foreach ($onePage->getParameters() as $oneParameter) {
                        if (empty($docBlocParams[$oneParameter->getName()])) {
                            info('Has Public API attribute but parameter $' . $oneParameter->getName() . ' is undocumented ' . $reflectionClass->getName() . '->' . $onePage->getName());
                        }
                    }
                }

                if (empty($this->getDocBlockDescription($onePage)) && $reflectionClass->getNamespaceName() !== '') {
                    note('Description is empty: ' . $onePage->class . '->' . $onePage->getName());
                }
            } elseif ($onePage->isPublic()) {
                $non_inDoc++;

                if ($phpDocNode && !$phpDocNode->hasAttribute('description') && $reflectionClass->getNamespaceName() !== '') {
                    // var_dump('Method not has API attribute: '.$reflectionClass->getName().'->'.$oneMethod->getName());
                }
            }
        }
    }


    // Build Methods

    protected function createMarkdownContent(ReflectionMethod|ReflectionProperty $onePage): string
    {
        $gitHubLinkClass = $onePage instanceof ReflectionProperty ? $onePage->getDeclaringClass() : $onePage;

        // Header
        $md =   '# ' . self::getModifiersName($onePage) . ' ' . self::simpleClass($onePage->class) . '::' . $onePage->name . "\n\n" .

                '> [Read it at the source](' . self::getGithubLink($gitHubLinkClass) . ")\n\n" .

                "## Description    \n\n" .
                self::computeRepresentationAsPHP($onePage) . "\n\n" .
                $this->getDocBlockDescription($onePage) . "\n";

        // Parameters
        if ($onePage instanceof ReflectionMethod && $onePage->getNumberOfParameters() > 0) {
            $docBlocParams = $this->getDocBlockParameters($onePage);

            $md .= "\n" . "## Parameter" . ($onePage->getNumberOfParameters() > 1 ? 's' : '') . "\n";

            foreach ($onePage->getParameters() as $value) {
                $pt = !empty($docBlocParams[$value->getName()]) ? $docBlocParams[$value->getName()] : '';

                $md .=  "\n" .
                        '### **' . $value->getName() . ':** *' . self::getTypeAsString($value->getType(), true) . "*   \n" .
                        $pt . "    \n";
            }
        }


        // Return Value

        if ($onePage instanceof ReflectionMethod && $this->hasDocBlockTag('@return', $onePage)) {
            $returnDescription = $this->getDocBlockTagDescriptionOrValue('@return', $onePage);
            $returnDescription = implode("\n", array_map(fn($in): string => ltrim($in), explode("\n", $returnDescription)));

            $md .= "\n\n" .
                    "## Return value   \n\n" .
                    '*(' . self::getTypeAsString($onePage->getReturnType(), true) . ')* ' . $returnDescription . "\n\n";
        }

        // Throw
        if ($this->hasDocBlockTag('@throws', $onePage)) {
            $md .=  "\n\n" .
                    "## Throws:   \n\n";

            foreach ($this->getPhpDocNode($onePage)->getTagsByName('@throws') as $oneTag) {
                $classPath = NamespaceResolver::resolveClassName((string) $oneTag->value->type, $onePage->getDeclaringClass()->getFileName());

                $md .= '* ```' . $classPath . '``` ' . ($oneTag->value->description ?? '') . "\n";
            }
        }

        // Related methods

        if (!empty($see = $this->getDocBlockTagDescriptionOrValue('@see', $onePage))) {
            $md .=  "\n" .
                    "---------------------------------------\n\n" .
                    "## Related method(s)      \n\n";

            foreach (explode(', ', $see) as $toSee) {
                if ($toSee === self::simpleClass($onePage->class) . '::' . $onePage->name) {
                    continue;
                }

                $md .= '* ' . $this->cleverRelated($toSee) . "    \n";
            }
        }

        if (!empty($book = $this->getDocBlockTagDescriptionOrValue('@book', $onePage))) {
            $md .=  "\n" .
                    "---------------------------------------\n\n" .
                    "## Tutorial\n\n";

            foreach (explode(', ', $this->getDocBlockTagDescriptionOrValue('@book', $onePage)) as $BookAttribute) {
                $BookLibrary = \constant($BookAttribute);

                $md .= '* **[This method has explanations and examples in the Documentation Book](' . $BookLibrary->value . ")**    \n";
            }
        }

        return $md;
    }

    protected function makeIndex(array $index): string
    {
        $file_content = '';

        $testPublicAttribute = function (ReflectionMethod|ReflectionProperty $reflectionMethod): bool {
            if (!$this->hasDocBlockTag('@api', $reflectionMethod)) {
                return false;
            }

            $apiDescription = $this->getDocBlockTagDescriptionOrValue('@api', $reflectionMethod);

            if (empty($apiDescription)) {
                return true;
            } elseif (str_contains($apiDescription, self::simpleClass($reflectionMethod->class))) {
                return true;
            }

            return false;
        };

        foreach ($index as $class => &$classMeta) {
            usort($classMeta['page'], static function (array $a, array $b): int {
                if ($a['Reflection']->isStatic() === $b['Reflection']->isStatic()) {
                    return strnatcmp($a['Reflection']->name, $b['Reflection']->name);
                } elseif ($a['Reflection']->isStatic() && !$b['Reflection']->isStatic()) {
                    return -1;
                } else {
                    return 1;
                }
            });

            $classWillBePublic = false;

            if ($this->hasDocBlockTag('@internal', $classMeta['ReflectionClass'])) {
                $classWillBePublic = false;
            }
            elseif ($this->hasDocBlockTag('@api', $classMeta['ReflectionClass'])) {
                $classWillBePublic = true;
            } else {
                foreach ($classMeta['page'] as $onePage) {
                    if ($testPublicAttribute($onePage['Reflection'])) {
                        $classWillBePublic = true;
                        break;
                    }
                }

                foreach ($classMeta['ReflectionClass']->getReflectionConstants() as $oneConstant) {
                    if ($this->hasDocBlockTag('@api', $oneConstant)) {
                        $classWillBePublic = true;
                        break;
                    }
                }

                foreach ($classMeta['ReflectionClass']->getProperties() as $oneProperty) {
                    if ($this->hasDocBlockTag('@api', $oneProperty)) {
                        $classWillBePublic = true;
                        break;
                    }
                }
            }

            if ($classWillBePublic) {
                $isEnum = enum_exists(($enumCases = $classMeta['ReflectionClass'])->name);

                $file_content .= "\n";
                $file_content .= '### CondorcetPHP\Condorcet\\' . $class . ' ' . ((!$isEnum) ? 'Class' : 'Enum') . "  \n\n";

                if ($isEnum) {
                    $file_content .= $this->makeEnumeCases(new \ReflectionEnum($enumCases->name), false);
                    $file_content .= "\n";
                } else {
                    $file_content .= $this->makeConstants($classMeta['ReflectionClass'], ReflectionClassConstant::IS_PUBLIC, true);
                }

                $file_content .= $this->makeProperties($classMeta['ReflectionClass'], null, true);
            }


            foreach ($classMeta['page'] as $onePage) {
                if (!$testPublicAttribute($onePage['Reflection']) || ($onePage instanceof ReflectionMethod && !$onePage['Reflection']->isUserDefined())) {
                    continue;
                } else {
                    $url = $this->getUrl($onePage['Reflection']);

                    $representation = self::computeRepresentationAsForIndex($onePage['Reflection']);
                    $file_content .= "* `$representation[0]` [" . $representation[1] . '](' . $url . ')';

                    if (isset($onePage['Reflection']) && $onePage['Reflection'] instanceof ReflectionMethod && $onePage['Reflection']->hasReturnType()) {
                        $file_content .= ': ' . self::getTypeAsString($onePage['Reflection']->getReturnType(), true);
                    }

                    $file_content .= "  \n";
                }
            }
        }

        return $file_content;
    }

    protected function getUrl(ReflectionMethod|ReflectionProperty $reflection): string
    {
        $url = str_replace('\\', '_', self::simpleClass($reflection->class)) . ' Class/' . str_replace('\\', '_', self::simpleClass($reflection->class) . '--' . $reflection->name) . '.md';
        $url = str_replace(' ', '%20', $url);

        return $this->pathBase . '/' . $url;
    }

    protected function makeEnumeCases(\ReflectionEnum $enumReflection, bool $shortName = false): string
    {
        $cases = $enumReflection->getCases();

        $r = '';

        foreach ($cases as $oneCase) {
            $name = ($shortName) ? $enumReflection->getShortName() : self::simpleClass($enumReflection->getName());
            $r .= '* case ' . $name . '::' . $oneCase->getName() . "  \n";
        }

        return $r;
    }

    protected function makeConstants(ReflectionClass $class, ?int $type = null, bool $mustHaveApiAttribute = false, bool $addMdCodeTag = true): string
    {
        $file_content = '';

        $hasConstants = false;

        foreach ($class->getReflectionConstants($type) as $constant) {
            if (!$mustHaveApiAttribute || $this->hasDocBlockTag('@api', $constant)) {
                $file_content .= '* ';
                $file_content .=  $addMdCodeTag ? '`' : '';

                $file_content .= $constant->isFinal() ? 'final ' : '';

                $file_content .= $constant->isPublic() ? 'public' : '';
                $file_content .= $constant->isProtected() ? 'protected' : '';
                $file_content .= $constant->isPrivate() ? 'private' : '';

                $file_content .= ' const ' . $constant->getName() . ': (' . \gettype($constant->getValue()) . ')';
                $file_content .= $addMdCodeTag ? '`  ' : '';
                $file_content .= "\n";
                $hasConstants = true;
            }
        }

        if ($hasConstants) {
            $file_content .= "\n";
        }

        return $file_content;
    }

    protected function makeProperties(ReflectionClass $class, ?int $type = null, bool $mustHaveApiAttribute = false, bool $forIndexSection = true): string
    {
        $file_content = '';

        $hasConstants = false;

        foreach ($class->getProperties($type) as $property) {

            $url = $this->getUrl($property);

            if (!$mustHaveApiAttribute || $this->hasDocBlockTag('@api', $property)) {
                $file_content .= '* ';
                $file_content .=  $forIndexSection ? '`' : '';

                $file_content .= $property->isReadOnly() ? 'readonly ' : '';

                $file_content .= $property->isPublic() ? 'public ' : '';
                $file_content .= $property->isProtected() ? 'protected ' : '';
                $file_content .= $property->isPrivate() ? 'private ' : '';

                $file_content .= $property->isStatic() ? 'static ' : '';

                $file_content .= $property->getType();
                $file_content .= $forIndexSection ? '`' : '';
                $file_content .= ' ';
                $file_content .= $forIndexSection ? '[' : '';
                $file_content .= '$';
                $file_content .= $property->getName();
                $file_content .= $forIndexSection ? '](' . $url . ')' : '';
                $file_content .= "\n";
                $hasConstants = true;
            }
        }

        if ($hasConstants) {
            $file_content .= "\n";
        }

        return $file_content;
    }

    protected function makeProfundis(array $index): string
    {
        $file_content = '';

        foreach ($index as $class => &$classMeta) {
            usort($classMeta['page'], static function (array $a, array $b): int {
                if ($a['static'] === $b['static']) {
                    if ($a['visibility_public'] && !$b['visibility_public']) {
                        return -1;
                    } elseif (!$a['visibility_public'] && $b['visibility_public']) {
                        return 1;
                    } else {
                        if ($a['visibility_protected'] && !$b['visibility_protected']) {
                            return -1;
                        } elseif (!$a['visibility_protected'] && $b['visibility_protected']) {
                            return 1;
                        } else {
                            return strnatcmp($a['name'], $b['name']);
                        }
                    }
                } elseif ($a['static'] && !$b['static']) {
                    return -1;
                } else {
                    return 1;
                }
            });

            $file_content .= "\n";
            $file_content .= '#### `';
            $file_content .= ($classMeta['ReflectionClass']->isAbstract()) ? 'Abstract ' : '';
            $file_content .= 'CondorcetPHP\Condorcet\\' . $class . ' ';

            $file_content .= ($p = $classMeta['ReflectionClass']->getParentClass()) ? 'extends ' . $p->name . ' ' : '';

            $interfaces = implode(', ', $classMeta['ReflectionClass']->getInterfaceNames());
            $file_content .= (!empty($interfaces)) ? 'implements ' . $interfaces : '';

            $file_content .= "`  \n";

            $file_content .= '> [Read it at the source](' . self::getGithubLink($classMeta['ReflectionClass']) . ")\n\n";

            $file_content .= "```php\n";

            $isEnum = enum_exists(($enumCases = $classMeta['ReflectionClass'])->name);

            if ($isEnum) {
                $file_content .= $this->makeEnumeCases(new \ReflectionEnum($enumCases->name), true);
                $file_content .= "\n";
            } else {
                $file_content .= $this->makeConstants(class: $classMeta['ReflectionClass'], addMdCodeTag: false);
            }

            $file_content .= $this->makeProperties(class: $classMeta['ReflectionClass'], forIndexSection: false);

            foreach ($classMeta['page'] as $oneMethod) {
                if ($oneMethod['Reflection'] instanceof ReflectionProperty || $oneMethod['Reflection']->isUserDefined()) {
                    $parameters =  $oneMethod['Reflection'] instanceof ReflectionMethod ? $oneMethod['Reflection']->getParameters() : [];
                    $parameters_string = '';

                    $i = 0;
                    foreach ($parameters as $oneP) {
                        $parameters_string .= (++$i > 1) ? ', ' : '';

                        if ($oneP->getType() !== null) {
                            $parameters_string .= self::getTypeAsString($oneP->getType()) . ' ';
                        }
                        $parameters_string .= '$' . $oneP->name;

                        if ($oneP->isDefaultValueAvailable()) {
                            $parameters_string .= ' = ' . self::speakBool($oneP->getDefaultValue());
                        }
                    }

                    $representation = ($oneMethod['visibility_public']) ? 'public ' : '';
                    $representation .= ($oneMethod['visibility_protected']) ? 'protected ' : '';
                    $representation .= ($oneMethod['visibility_private']) ? 'private ' : '';

                    $representation .=  ($oneMethod['static']) ? 'static ' : '';
                    $representation .=  $oneMethod['name'] . ' (' . $parameters_string . ')';

                    if ($oneMethod['Reflection'] instanceof ReflectionMethod && $oneMethod['Reflection']->hasReturnType()) {
                        $representation .= ': ' . self::getTypeAsString($oneMethod['Reflection']->getReturnType());
                    }

                    $file_content .= '* ' . $representation . "  \n";
                }
            }

            $file_content .= "```\n";
        }

        return $file_content;
    }

    protected function getPhpDocNode(string|ReflectionClass|ReflectionMethod|ReflectionProperty $docBlock): PhpDocNode
    {
        if ($docBlock instanceof Reflector) {
            $docBlock = $docBlock->getDocComment();
        }

        $tokens = new TokenIterator($this->lexer->tokenize($docBlock));
        return $this->phpDocParser->parse($tokens);
    }

    protected function getDocBlockTags(ReflectionClass|ReflectionMethod|ReflectionClassConstant|ReflectionProperty $source): array
    {
        $docComment = $source->getDocComment();

        $tags = [];

        if ($docComment !== false) {
            $phpDocNode = $this->getPhpDocNode($docComment);
            $tagsNode = $phpDocNode->getTags();

            foreach ($tagsNode as $tagNode) {
                $tags[] = $tagNode->name;
            }
        }

        return $tags;
    }

    protected function hasDocBlockTag(string $tag, ReflectionClass|ReflectionMethod|ReflectionClassConstant|ReflectionProperty $source): bool
    {
        return \in_array($tag, $this->getDocBlockTags($source), true);
    }

    protected function getDocBlockTagDescriptionOrValue(string $tag, ReflectionClass|ReflectionMethod|ReflectionProperty $source): null|false|string
    {
        if (!$this->hasDocBlockTag($tag, $source)) {
            return false;
        }

        $tagsNode =  $this->getPhpDocNode($source->getDocComment())->getTagsByName($tag);

        $node = array_pop($tagsNode);

        return $node->value->description ?? $node->value->value ?? null;
    }

    protected function getDocBlockDescription(PhpDocNode|ReflectionClass|ReflectionMethod|ReflectionProperty $source): false|string
    {
        if ($source instanceof Reflector) {
            $source = $source->getDocComment();
            $source = $source === false ? false : $this->getPhpDocNode($source);
        }

        if ($source === false) {
            return false;
        }

        $textNodes = array_filter($source->children, fn(Node $node): bool => $node instanceof PhpDocTextNode);
        $lines = array_map(fn(PhpDocTextNode $textNode): string => $textNode->text, $textNodes);
        $nonBlankLines = array_filter($lines);

        return empty($nonBlankLines) ? false : implode(\PHP_EOL, $nonBlankLines);
    }

    protected function getDocBlockParameters(ReflectionMethod $method): array
    {
        $phpDocNode = $this->getPhpDocNode($method);

        $paramsTagValueNode = array_merge($phpDocNode->getTypelessParamTagValues(), $phpDocNode->getParamTagValues()); // ParamTagValueNode[]

        $params = [];

        foreach ($paramsTagValueNode as $paramTagValueNode) {
            $params[str_replace('$', '', $paramTagValueNode->parameterName)] = $paramTagValueNode->description ?? null;
        }

        return $params;
    }
}
