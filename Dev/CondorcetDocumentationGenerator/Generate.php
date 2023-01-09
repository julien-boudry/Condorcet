<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Book, Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related, Throws};
use HaydenPierce\ClassFinder\ClassFinder;

class Generate
{
    public const BOOK_URL = 'https://www.condorcet.io';
    public const GITHUB_BASE = 'https://github.com/julien-boudry/Condorcet';
    public const GITHUB_BRANCH_PATH = '/blob/master/';

    // Static - Translators

    public static function makeFilename(\ReflectionMethod $method): string
    {
        return self::getModifiersName($method).
                ' '.
                str_replace('\\', '_', self::simpleClass($method->class)).'--'.$method->name.
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
            return '['.implode(',', $c).']';
        }
        if (\is_object($c)) {
            return 'new '.$c::class;
        }

        return (string) $c;
    }

    public static function getTypeAsString(?\ReflectionType $rf_rt, bool $codeBlock = false): ?string
    {
        if ($rf_rt !== null) {
            if ($codeBlock) {
                return '```'.((string) $rf_rt).'```';
            } else {
                return (string) $rf_rt;
            }
        }

        return $rf_rt;
    }

    public static function getGithubLink(\ReflectionFunctionAbstract $refl): string {
        $link = self::GITHUB_BASE.self::GITHUB_BRANCH_PATH .
                substr($refl->getFileName(), mb_strpos($refl->getFileName(), '/src/') + 1) .
                '#L'.$refl->getStartLine()
                ;

        return $link;
    }

    public static function getModifiersName(\ReflectionMethod $method): string
    {
        return implode(' ', \Reflection::getModifierNames($method->getModifiers()));
    }

    // Static - Builder

    public static function cleverRelated(string $name, string $path): string
    {
        $infos = explode('::', $name);
        $infos[0] = str_replace('static ', '', $infos[0]);

        $url = $path.'/'.$infos[0].' Class/public '.str_replace('::', '--', $name) . '.md';
        $url = str_replace(' ', '%20', $url);

        return '['.$name.']('.$url.')';
    }

    public static function computeRepresentationAsForIndex(\ReflectionMethod $method): string
    {
        return self::getModifiersName($method).
                ' '.
                self::simpleClass($method->class).
                (($method->isStatic()) ? '::' : '->').
                $method->name.
                ' ('.(($method->getNumberOfParameters() > 0) ? '...' : '').')';
    }

    public static function computeRepresentationAsPHP(\ReflectionMethod $method): string
    {
        $option = false;
        $str = '(';
        $i = 0;


        if ($method->getNumberOfParameters() > 0) {
            foreach ($method->getParameters() as $value) {
                $str .= ' ';
                $str .= ($value->isOptional() && !$option) ? '[' : '';
                $str .= ($i > 0) ? ', ' : '';
                $str .= self::getTypeAsString($value->getType());
                $str .= ' ';
                $str .= $value->isPassedByReference() ? '&' : '';
                $str .= '$'.$value->getName();
                $str .= $value->isDefaultValueAvailable() ? ' = '.self::speakBool($value->getDefaultValue()) : '';

                ($value->isOptional() && !$option) ? $option = true : null;
                $i++;
            }
        }

        if ($option) {
            $str .= ']';
        }

        $str .= ' )';

        return "```php\n".
                self::getModifiersName($method).' '.self::simpleClass($method->class).(($method->isStatic()) ? '::' : '->').$method->name.' '.$str. ((self::getTypeAsString($method->getReturnType()) !== null) ? ': '.self::getTypeAsString($method->getReturnType()) : '').
                "\n```";
    }


    // Script

    public function __construct($path, public readonly string $pathBase)
    {
        $start_time = microtime(true);

        $pathDirectory = $path.\DIRECTORY_SEPARATOR;

        //
        $FullClassList = ClassFinder::getClassesInNamespace('CondorcetPHP\Condorcet\\', ClassFinder::RECURSIVE_MODE);
        $FullClassList = array_filter($FullClassList, static function (string $value) {
            return (mb_strpos($value, 'Condorcet\Test') === false) && (mb_strpos($value, 'Condorcet\Dev') === false);
        });

        $inDoc = 0;
        $non_inDoc = 0;
        $total_methods = 0;
        $total_nonInternal_methods = 0;

        // Warnings
        foreach ($FullClassList as $FullClass) {
            $methods = (new \ReflectionClass($FullClass))->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $oneMethod) {
                if ($oneMethod->isInternal()) {
                } elseif (!empty($oneMethod->getAttributes(PublicAPI::class))) {
                    $inDoc++;

                    if ($oneMethod->getNumberOfParameters() > 0) {
                        foreach ($oneMethod->getParameters() as $oneParameter) {
                            if (empty($oneParameter->getAttributes(FunctionParameter::class))) {
                                var_dump('Method Has Public API attribute but parameter $'.$oneParameter->getName().' is undocumented '.$oneMethod->getDeclaringClass()->getName().'->'.$oneMethod->getName());
                            }
                        }
                    }

                    if (empty($oneMethod->getAttributes(Description::class)) && $oneMethod->getDeclaringClass()->getNamespaceName() !== '') {
                        var_dump('Description Attribute is empty: '.$oneMethod->getDeclaringClass()->getName().'->'.$oneMethod->getName());
                    }
                } else {
                    $non_inDoc++;

                    if (empty($oneMethod->getAttributes(PublicAPI::class)) && $oneMethod->getDeclaringClass()->getNamespaceName() !== '') {
                        // var_dump('Method not has API attribute: '.$oneMethod->getDeclaringClass()->getName().'->'.$oneMethod->getName());
                    }
                }
            }
        }

        $full_methods_list = [];

        // generate .md
        foreach ($FullClassList as $FullClass) {
            $reflectionClass = new \ReflectionClass($FullClass);
            $methods = ($reflectionClass)->getMethods();
            $shortClass = str_replace('CondorcetPHP\Condorcet\\', '', $FullClass);

            $full_methods_list[$shortClass] = [
                'FullClass' => $FullClass,
                'shortClass' => $shortClass,
                'ReflectionClass' => $reflectionClass,
                'methods' => [],
            ];

            foreach ($methods as $oneMethod) {
                $method_array = $full_methods_list[$shortClass]['methods'][$oneMethod->name] = [
                    'FullClass' => $FullClass,
                    'shortClass' => $shortClass,
                    'name' => $oneMethod->name,
                    'static' => $oneMethod->isStatic(),
                    'visibility_public' => $oneMethod->isPublic(),
                    'visibility_protected' => $oneMethod->isProtected(),
                    'visibility_private' => $oneMethod->isPrivate(),
                    'ReflectionMethod' => $oneMethod,
                    'ReflectionClass' => $oneMethod->getDeclaringClass(),
                ];

                $total_methods++;

                if (!$oneMethod->isInternal()) {
                    $total_nonInternal_methods++;
                }

                // Write Markdown
                if (!empty($apiAttribute = $oneMethod->getAttributes(PublicAPI::class)) && (empty($apiAttribute[0]->getArguments()) || \in_array(self::simpleClass($oneMethod->class), $apiAttribute[0]->getArguments(), true))) {
                    $path = $pathDirectory . str_replace('\\', '_', self::simpleClass($oneMethod->class)) . ' Class/';

                    if (!is_dir($path)) {
                        mkdir($path);
                    }

                    file_put_contents($path.self::makeFilename($oneMethod), $this->createMarkdownContent($oneMethod, $method_array));
                }
            }
        }


        print 'Public methods in doc: '.$inDoc.' / '.($inDoc + $non_inDoc).' | Total non-internal methods count: '.$total_nonInternal_methods.' | Number of Class: '.\count($FullClassList).' | Number of Methods including internals: '.$total_methods."\n";

        // Add Index
        $file_content =  "> **[Presentation](../README.md) | [Documentation Book](".self::BOOK_URL.") | API References | [Voting Methods](/Docs/VotingMethods.md) | [Tests](https://github.com/julien-boudry/Condorcet/tree/master/Tests)**\n\n".

                        "# API References\n".
                        "## Public API Index *\n".

                        "_*: I try to update and complete the documentation. See also [the documentation book](".self::BOOK_URL."), [the tests](../Tests) also produce many examples. And create issues for questions or fixing documentation!_\n\n";


        $file_content .= $this->makeIndex($full_methods_list);

        $file_content .= "\n\n\n";

        uksort($full_methods_list, 'strnatcmp');
        $file_content .=    "## Full Class & API References\n".
                            "_Including above methods from public API_\n\n";

        $file_content .= $this->makeProfundis($full_methods_list);


        // Write file
        file_put_contents($pathDirectory.'README.md', $file_content);


        echo 'YAH ! <br>' . (microtime(true) - $start_time) .'s';
    }


    // Build Methods

    protected function createMarkdownContent(\ReflectionMethod $method, array $entry): string
    {
        // Header
        $md =   '## '.self::getModifiersName($method).' '. self::simpleClass($method->class).'::'.$method->name."\n\n".

                "> [Read it at the source](".self::getGithubLink($method).")\n\n".

                "### Description    \n\n".
                self::computeRepresentationAsPHP($method)."\n\n".
                $method->getAttributes(Description::class)[0]->getArguments()[0]."\n    ";

        // Input
        if ($method->getNumberOfParameters() > 0) {
            foreach ($method->getParameters() as $key => $value) {
                if (!empty($attributes = $value->getAttributes(FunctionParameter::class))) {
                    $pt = $attributes[0]->newInstance()->text;
                } elseif (isset($entry['input'][$value->getName()]['text'])) {
                    $pt = $entry['input'][$value->getName()]['text'];
                } else {
                    $pt = '';
                }

                $md .=  "\n\n".
                        '#### **'.$value->getName().':** *'.self::getTypeAsString($value->getType(), true)."*   \n".
                        $pt."    \n";
            }
        }


        // Return Value

        if (!empty($method->getAttributes(FunctionReturn::class))) {
            $md .= "\n\n".
                    "### Return value:   \n\n".
                    '*('.self::getTypeAsString($method->getReturnType(), true).')* '.$method->getAttributes(FunctionReturn::class)[0]->getArguments()[0]."\n\n";
        }

        // Throw
        if (!empty($method->getAttributes(Throws::class))) {
            $md .=  "\n\n".
                    "### Throws:   \n\n";

            foreach ($method->getAttributes(Throws::class)[0]->getArguments() as $arg) {
                $md .= '* ```'.$arg."```\n";
            }
        }

        // Related methods

        if (!empty($method->getAttributes(Related::class))) {
            $md .=  "\n".
                    "---------------------------------------\n\n".
                    "### Related method(s)      \n\n";

            foreach ($method->getAttributes(Related::class) as $RelatedAttribute) {
                foreach ($RelatedAttribute->newInstance()->relatedList as $value) {
                    if ($value === self::simpleClass($method->class).'::'.$method->name) {
                        continue;
                    }

                    $md .= '* '.self::cleverRelated($value, $this->pathBase)."    \n";
                }
            }
        }

        if (!empty($method->getAttributes(Book::class))) {
            $md .=  "\n".
                    "---------------------------------------\n\n".
                    "### Tutorial\n\n";

            foreach ($method->getAttributes(Book::class) as $BookAttribute) {
                $BookAttribute = $BookAttribute->newInstance();

                $md .= '* **[This method has explanations and examples in the Documentation Book]('.$BookAttribute->chapter->value.")**    \n";
            }
        }

        if (!empty($method->getAttributes(Example::class))) {
            $md .=  "\n".
                    "---------------------------------------\n\n".
                    "### Examples and explanation\n\n";

            foreach ($method->getAttributes(Example::class) as $ExampleAttribute) {
                $ExampleAttribute = $ExampleAttribute->newInstance();

                $md .= '* **['.$ExampleAttribute->name.']('.$ExampleAttribute->link.")**    \n";
            }
        }


        return $md;
    }

    protected function makeIndex(array $index): string
    {
        $file_content = '';

        $testPublicAttribute = static function (\ReflectionMethod $reflectionMethod): bool {
            return !(empty($apiAttribute = $reflectionMethod->getAttributes(PublicAPI::class)) || (!empty($apiAttribute[0]->getArguments()) && !\in_array(self::simpleClass($reflectionMethod->class), $apiAttribute[0]->getArguments(), true)));
        };

        foreach ($index as $class => &$classMeta) {
            usort($classMeta['methods'], static function (array $a, array $b): int {
                if ($a['ReflectionMethod']->isStatic() === $b['ReflectionMethod']->isStatic()) {
                    return strnatcmp($a['ReflectionMethod']->name, $b['ReflectionMethod']->name);
                } elseif ($a['ReflectionMethod']->isStatic() && !$b['ReflectionMethod']->isStatic()) {
                    return -1;
                } else {
                    return 1;
                }
            });

            $classWillBePublic = false;

            if ($classMeta['ReflectionClass']->getAttributes(PublicAPI::class)) {
                $classWillBePublic = true;
            } else {
                foreach ($classMeta['methods'] as $oneMethod) {
                    if ($testPublicAttribute($oneMethod['ReflectionMethod'])) {
                        $classWillBePublic = true;
                        break;
                    }
                }

                foreach ($classMeta['ReflectionClass']->getReflectionConstants() as $oneConstant) {
                    if (!empty($oneConstant->getAttributes(PublicAPI::class))) {
                        $classWillBePublic = true;
                        break;
                    }
                }

                foreach ($classMeta['ReflectionClass']->getProperties() as $onePropertie) {
                    if (!empty($onePropertie->getAttributes(PublicAPI::class))) {
                        $classWillBePublic = true;
                        break;
                    }
                }
            }

            if ($classWillBePublic) {
                $isEnum = enum_exists(($enumCases = $classMeta['ReflectionClass'])->name);

                $file_content .= "\n";
                $file_content .= '### CondorcetPHP\Condorcet\\'.$class.' '.((!$isEnum) ? 'Class' : 'Enum')."  \n\n";

                if ($isEnum) {
                    $file_content .= $this->makeEnumeCases(new \ReflectionEnum($enumCases->name), false);
                    $file_content .= "\n";
                } else {
                    $file_content .= $this->makeConstants($classMeta['ReflectionClass'], \ReflectionClassConstant::IS_PUBLIC, true);
                }

                $file_content .= $this->makeProperties($classMeta['ReflectionClass'], null, true);
            }


            foreach ($classMeta['methods'] as $oneMethod) {
                if (!$testPublicAttribute($oneMethod['ReflectionMethod']) || !$oneMethod['ReflectionMethod']->isUserDefined()) {
                    continue;
                } else {
                    $url = str_replace('\\', '_', self::simpleClass($oneMethod['ReflectionMethod']->class)).' Class/'.self::getModifiersName($oneMethod['ReflectionMethod']).' '. str_replace('\\', '_', self::simpleClass($oneMethod['ReflectionMethod']->class).'--'. $oneMethod['ReflectionMethod']->name) . '.md';
                    $url = str_replace(' ', '%20', $url);
                    $url = $this->pathBase.'/'.$url;

                    $file_content .= '* ['.self::computeRepresentationAsForIndex($oneMethod['ReflectionMethod']).']('.$url.')';

                    if (isset($oneMethod['ReflectionMethod']) && $oneMethod['ReflectionMethod']->hasReturnType()) {
                        $file_content .= ': '.self::getTypeAsString($oneMethod['ReflectionMethod']->getReturnType(), true);
                    }

                    $file_content .= "  \n";
                }
            }
        }

        return $file_content;
    }

    protected function makeEnumeCases(\ReflectionEnum $enumReflection, bool $shortName = false): string
    {
        $cases = $enumReflection->getCases();

        $r = '';

        foreach ($cases as $oneCase) {
            $name = ($shortName) ? $enumReflection->getShortName() : self::simpleClass($enumReflection->getName());
            $r .= '* ```case '.$name.'::'.$oneCase->getName()."```  \n";
        }

        return $r;
    }

    protected function makeConstants(\ReflectionClass $class, ?int $type = null, bool $mustHaveApiAttribute = false, bool $addMdCodeTag = true): string
    {
        $file_content = '';

        $hasConstants = false;

        foreach ($class->getReflectionConstants($type) as $constant) {
            if (!$mustHaveApiAttribute || !empty($constant->getAttributes(PublicAPI::class))) {
                $file_content .= '* ';
                $file_content .=  $addMdCodeTag ? '```' : '';

                $file_content .= $constant->isFinal() ? 'final ' : '';

                $file_content .= $constant->isPublic() ? 'public' : '';
                $file_content .= $constant->isProtected() ? 'protected' : '';
                $file_content .= $constant->isPrivate() ? 'private' : '';

                $file_content .= ' const '.$constant->getName().': ('.\gettype($constant->getValue()).')';
                $file_content .= $addMdCodeTag ? '```  ' : '';
                $file_content .= "\n";
                $hasConstants = true;
            }
        }

        if ($hasConstants) {
            $file_content .= "\n";
        }

        return $file_content;
    }

    protected function makeProperties(\ReflectionClass $class, ?int $type = null, bool $mustHaveApiAttribute = false, bool $addMdCodeTag = true): string
    {
        $file_content = '';

        $hasConstants = false;

        foreach ($class->getProperties($type) as $propertie) {
            if (!$mustHaveApiAttribute || !empty($propertie->getAttributes(PublicAPI::class))) {
                $file_content .= '* ';
                $file_content .=  $addMdCodeTag ? '```' : '';

                $file_content .= $propertie->isReadOnly() ? 'readonly ' : '';

                $file_content .= $propertie->isPublic() ? 'public ' : '';
                $file_content .= $propertie->isProtected() ? 'protected ' : '';
                $file_content .= $propertie->isPrivate() ? 'private ' : '';

                $file_content .= $propertie->isStatic() ? 'static ' : '';

                $file_content .= ((string) $propertie->getType()).' $'.$propertie->getName();
                $file_content .= $addMdCodeTag ? '```  ' : '';
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
            usort($classMeta['methods'], static function (array $a, array $b): int {
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
            $file_content .= '#### ';
            $file_content .= ($classMeta['ReflectionClass']->isAbstract()) ? 'Abstract ' : '';
            $file_content .= 'CondorcetPHP\Condorcet\\'.$class.' ';

            $file_content .= ($p = $classMeta['ReflectionClass']->getParentClass()) ? 'extends '.$p->name.' ' : '';

            $interfaces = implode(', ', $classMeta['ReflectionClass']->getInterfaceNames());
            $file_content .= (!empty($interfaces)) ? 'implements '.$interfaces : '';

            $file_content .= "  \n";
            $file_content .= "```php\n";

            $isEnum = enum_exists(($enumCases = $classMeta['ReflectionClass'])->name);

            if ($isEnum) {
                $file_content .= $this->makeEnumeCases(new \ReflectionEnum($enumCases->name), true);
                $file_content .= "\n";
            } else {
                $file_content .= $this->makeConstants(class: $classMeta['ReflectionClass'], addMdCodeTag: false);
            }

            $file_content .= $this->makeProperties(class: $classMeta['ReflectionClass'], addMdCodeTag: false);

            foreach ($classMeta['methods'] as $oneMethod) {
                if ($oneMethod['ReflectionMethod']->isUserDefined()) {
                    $parameters = $oneMethod['ReflectionMethod']->getParameters();
                    $parameters_string = '';

                    $i = 0;
                    foreach ($parameters as $oneP) {
                        $parameters_string .= (++$i > 1) ? ', ' : '';

                        if ($oneP->getType() !== null) {
                            $parameters_string .= self::getTypeAsString($oneP->getType()) . ' ';
                        }
                        $parameters_string .= '$'.$oneP->name;

                        if ($oneP->isDefaultValueAvailable()) {
                            $parameters_string .= ' = '.self::speakBool($oneP->getDefaultValue());
                        }
                    }

                    $representation = ($oneMethod['visibility_public']) ? 'public ' : '';
                    $representation .= ($oneMethod['visibility_protected']) ? 'protected ' : '';
                    $representation .= ($oneMethod['visibility_private']) ? 'private ' : '';

                    $representation .=  ($oneMethod['static']) ? 'static ' : '';
                    $representation .=  $oneMethod['name'] . ' ('.$parameters_string.')';

                    if ($oneMethod['ReflectionMethod']->hasReturnType()) {
                        $representation .= ': '.self::getTypeAsString($oneMethod['ReflectionMethod']->getReturnType());
                    }

                    $file_content .= '* '.$representation."  \n";
                }
            }

            $file_content .= "```\n";
        }

        return $file_content;
    }
}
