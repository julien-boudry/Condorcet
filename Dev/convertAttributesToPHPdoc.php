<?php
/**
 * Script to convert custom attribute-based documentation
 * to standard PHPdoc comments in all PHP files inside /src.
 *
 * Usage: php convertAttributesToPHPdoc.php
 */

 namespace CondorcetPHP\Condorcet\Dev;


// Configuration array for allowed attribute names
$allowedAttributes = [
	'PublicAPI',
	'Description',
	'FunctionParameter',
	'FunctionReturn',
	'Throws',
	'Related',
    'InternalModulesAPI',
    'Example',
    'Book',
];

$sourceDir = __DIR__ . '/../src';

$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sourceDir));
foreach ($iterator as $file) {
    if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'php') {
        $filePath = $file->getRealPath();
        $content  = file_get_contents($filePath);
        $newContent = convertAttributesToPhpdoc($content);
        if ($newContent !== $content) {
            file_put_contents($filePath, $newContent);
            echo "Updated: " . $filePath . "\n";
        }
    }
}

echo "Conversion complete.\n";

/**
 * Converts attribute blocks (only those listed in the configuration array)
 * to PHPdoc comments. The attributes are kept intact.
 */
function convertAttributesToPhpdoc(string $content): string {
    global $allowedAttributes; // import configuration array

    // Regex finds block of one or several attribute lines preceding a definition
    $pattern = '/((?:\s*#\[[^\]]+\]\s*\n)+)(\s*(?:abstract\s+|final\s+)?(?:public|protected|private\s+)?\s*(?:static\s+)?\s*(?:function|class|trait)[^{]+)/i';

    $newContent = preg_replace_callback($pattern, function($matches) use ($allowedAttributes) {
         $attributesBlock = $matches[1];
         $definitionLine  = $matches[2];
         $parameters = extractParameters($definitionLine);

         // Process each attribute line based on the allowed configuration array.
         $lines = preg_split('/\R/', $attributesBlock);
         $annotations = [];
         foreach ($lines as $line) {
             $line = trim($line);
             if (preg_match('/#\[\s*(\w+)(?:\((.*)\))?\]/', $line, $attrMatches)) {
                  $attrName  = $attrMatches[1];
                  // Only process attributes that are in the allowed list.
                  if (!in_array($attrName, $allowedAttributes)) {
                      continue;
                  }

                  $attrValue = isset($attrMatches[2]) ? trim($attrMatches[2], " \"'") : '';

                  // Map attribute names to PHPdoc annotations
                  switch($attrName) {
                      case 'PublicAPI':
                          $annotations[] = ' @api ' . str_replace(['"',"'"], '', $attrValue);
                          break;
                      case 'Description':
                          foreach (explode("\\n", $attrValue) as $descriptionLine) {
                              $annotations[] = ' ' . $descriptionLine;
                          }
                          break;
                      case 'FunctionReturn':
                          $annotations[] = ' @return mixed ' . $attrValue;
                          break;
                      case 'Throws':
                          foreach (explode(',', $attrValue) as $oneThrow) {
                            $oneThrow = trim($oneThrow);
                            $annotations[] = ' @throws ' . str_replace('::class', '', $oneThrow);
                          }
                          break;
                      case 'Related':
                          $annotations[] = ' @see ' . str_replace(['"',"'"], '', $attrValue);
                          break;
                      case 'InternalModulesAPI':
                        $annotations[] = ' @internal ' . $attrValue;
                        break;
                      default:
                          $annotations[] = ' @' . mb_strtolower($attrName) . ' ' . str_replace(['"',"'"], '', $attrValue);
                  }
             }
         }

         foreach($parameters as $paramName => $paramDescription) {
             $annotations[] = ' @param ' . '$' . $paramName . ' ' . $paramDescription;
         }

         usort($annotations, function(string $a, string $b): int {
            $a = mb_trim($a);
            $b = mb_trim($b);

            if (str_starts_with($a, '@') && !str_starts_with($b, '@')) {
                return 1;
            }
            elseif (!str_starts_with($a, '@') && str_starts_with($b, '@')) {
                 return -1;
             }
             else {
                 return 0;
             }
         });

         // If no allowed attributes were found, return original content.
         if (empty($annotations)) {
             return $matches[0];
         }
         // Build the new PHPdoc block
         $phpdoc  = "\n"; // Ensure at least one blank line before the PHPdoc block.
         $phpdoc .= "/**\n";
         foreach ($annotations as $ann) {
            $phpdoc .= " *" . $ann . "\n";
         }
         $phpdoc .= " */";


         // Inverse l'ordre : docblock généré AVANT les attributs existants
         if (false && str_contains($attributesBlock, '#[\Override]')) {
            return $phpdoc . "\n#[\\Override]\n" . $definitionLine;
         }
         else {
            return $phpdoc . $attributesBlock . $definitionLine;
         }
    }, $content);

    return $newContent;
}

function extractParameters(string $code): array
{
    $pattern = '/#\[FunctionParameter\(\'([^\']+)\'\)\]\s+.*?\s+\$(\w+)/';
    preg_match_all($pattern, $code, $matches, PREG_SET_ORDER);

    $parameters = [];
    foreach ($matches as $match) {
        $parameters[$match[2]] = (mb_substr($match[1], -1) === '.') ? $match[1] : $match[1] . '.';
    }

    return $parameters;
}