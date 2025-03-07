<?php
/**
 * Script to convert custom attribute-based documentation
 * to standard PHPdoc comments in all PHP files inside /src.
 *
 * Usage: php convertAttributesToPHPdoc.php
 */

// Configuration array for allowed attribute names
$allowedAttributes = [
	'PublicAPI',
	'Description',
	'FunctionParameter',
	'FunctionReturn',
	'Throws',
	'Related',
    'InternalModulesApi',
    'Example',
    'Book',
];

$sourceDir = __DIR__ . '/../../src';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir));
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
                          $annotations[] = ' @public';
                          break;
                      case 'Description':
                          $annotations[] = ' ' . $attrValue;
                          break;
                      case 'FunctionParameter':
                          $annotations[] = ' @param ' . $attrValue;
                          break;
                      case 'FunctionReturn':
                          $annotations[] = ' @return ' . $attrValue;
                          break;
                      case 'Throws':
                          $annotations[] = ' @throws ' . $attrValue;
                          break;
                      case 'Related':
                          $annotations[] = ' @see ' . $attrValue;
                          break;
                      default:
                          $annotations[] = " @$attrName " . $attrValue;
                  }
             }
         }
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
         return $phpdoc . $attributesBlock . $definitionLine;
    }, $content);

    return $newContent;
}