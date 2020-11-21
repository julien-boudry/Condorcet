<?php
declare(strict_types=1);

use CondorcetPHP\Condorcet\CondorcetDocAttributes\{PublicAPI};
use HaydenPierce\ClassFinder\ClassFinder;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__.str_replace('/',DIRECTORY_SEPARATOR,'/../vendor/../vendor/autoload.php');


$doc = Yaml::parseFile(__DIR__.'/../Documentation/doc.yaml');

// Header & Prefix
$header = $doc[0]['header'];
unset($doc[0]);

$undocumented_prefix = $doc[1]['undocumented_prefix'] . "\n";
unset($doc[1]);


// 
$index  = [];

foreach ($doc as &$entry) :
  if (isset($entry['publish']) && $entry['publish'] !== true) :
    continue;
  endif;

  if (!is_array($entry['class'])) :
    $entry['class'] = [$entry['class']];
  endif;

  foreach ($entry['class'] as $class) :
    $method = $entry ;
    $method['class'] = $class;

    $index[$method['class']][$method['name']] = $method;
  endforeach;
endforeach;


$patternReplaceLastNewLine = '/(.*)\n$/';

foreach ($index as $ClassName => $ClassData) :

    $path_to_file = [__DIR__.'/../lib/'.str_replace('/','\\',$ClassName).'.php'];

    if ($ClassName === 'Election') :
        $path_to_file[] = __DIR__.'/../lib/ElectionProcess/CandidatesProcess.php';
        $path_to_file[] = __DIR__.'/../lib/ElectionProcess/VotesProcess.php';
        $path_to_file[] = __DIR__.'/../lib/ElectionProcess/ResultsProcess.php';
    endif;

    foreach ($path_to_file as $oneFile) :

        foreach($ClassData as $MethodName => $MethodData) :
            var_dump($ClassName.'->'.$MethodName);

            $description = $MethodData['description'];
            $description = str_replace("\$","\\\\$", $description);
            $description = preg_replace($patternReplaceLastNewLine, '$1', $description);
            $description = str_replace(["\n    ","\n"],"\\n ",$description);
            $description = str_replace('"','\"',$description);

            $attributes =  '$3#[Description("'.$description.'")]$2';

            if (isset($MethodData['return'])) :
                $returnV = $MethodData['return'];
                $returnV = preg_replace($patternReplaceLastNewLine, '$1', $returnV);
                $returnV = str_replace(["\n    ","\n"],"\\n ",$returnV);
                $returnV = str_replace('"','\"',$returnV);

                $attributes .= '$3#[FunctionReturn("'.$returnV.'")]$2';
            endif;


            if (isset($MethodData['examples'])) :
                $examples = $MethodData['examples'];
                $examples = preg_replace($patternReplaceLastNewLine, '$1', $examples);
                $examples = str_replace(["\n    ","\n"],"\\n ",$examples);
                $examples = str_replace('"','\"',$examples);

                $arg = '';
                $i = 1;
                foreach($examples as $oneExample) :
                  if ($i++ > 1) :
                    $arg .= ', ';
                  endif;

                  $arg .= '"'.$oneExample.'"';
                endforeach;

                $attributes .= '$3#[Examples('.$arg.')]$2';
            endif;

            if (isset($MethodData['related'])) :
              $related = $MethodData['related'];
              $related = preg_replace($patternReplaceLastNewLine, '$1', $related);
              $related = str_replace(["\n    ","\n"],"\\n ",$related);
              $related = str_replace('"','\"',$related);

              $arg = '';
              $i = 1;
              foreach($related as $oneRelated) :
                if ($i++ > 1) :
                  $arg .= ', ';
                endif;

                $arg .= '"'.$oneRelated.'"';
              endforeach;

              $attributes .= '$3#[Related('.$arg.')]$2';
          endif;


            $pattern = '/(#\[PublicAPI\])(\n)(.*)(public function '.$MethodName.')/';
            $replacement = '$1$2'.$attributes.'$3$4';
            
            $file_contents = file_get_contents($oneFile);
            $file_contents = preg_replace(
                    $pattern
                ,   $replacement
                ,   $file_contents);
            file_put_contents($oneFile,$file_contents);
        endforeach;

    endforeach;

endforeach;