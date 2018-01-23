<?php
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('display_errors', 1);
error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE); 

$start_time = microtime(true);

// Composer Autoload
require_once '../../vendor/autoload.php';

$doc = Spyc::YAMLLoad('doc.yaml');

foreach ($doc as $entry) :
  if (!isset($entry['publish']) || $entry['publish'] !== true) :
    continue;
  endif;

  if (!is_array($entry['class'])) :
    $entry['class'] = array($entry['class']);
  endif;

  foreach ($entry['class'] as $class) :
  	$method = $entry ;
    $method['class'] = $class;

  	$path = "../" . str_replace("\\", "_", $method['class']) . " Class/";

    if (!is_dir($path)) :
        mkdir($path);
    endif;

  	file_put_contents($path.makeFilename($method), createMarkdownContent($method));
  endforeach;
endforeach;

echo 'YAH ! <br>' . (microtime(true) - $start_time) .'s';

function makeFilename ($method) {
  return  $method['visibility'].
          (($method['static']) ? " static " : " "). 
          str_replace("\\", "_", $method['class'])."--".$method['name'].
          ".md";
}

function speakBool ($c)
{
  if ($c === true) : return 'true'; endif;
  if ($c === false) : return 'false'; endif;
  if ($c === null) : return 'null'; endif;

  return $c;
}

function computeCleverSpec ($static, $public, $class, $method, $param) {

	$option = false;
	$str = '(';
	$i = 0;

if (is_array($param)) :	foreach ($param as $key => $value) :
		
		$str .= ($value['required'] === false && !$option) ? " [" : "";
		$str .= ($i > 0) ? "," : "";
		$str .= " ";
		$str .= $value['type'];
		$str .= " ";
		$str .= $key;
		$str .= (isset($value['default'])) ? " = ".speakBool($value['default']) : "";

		if ($value['required'] === false && !$option) { $option = true; }
		$i++;
	endforeach;
endif;

	if ($option) {
		$str .= "]";
	}

	$str .= " )";


	return "```php
".$public." ".(($static)?"static ":'$').$class.(($static)?"::":' -> ').$method." ".$str."
```";
}

function cleverRelated ($name)
{
  $infos = explode('::', $name);
  $infos[0] = str_replace('static ', '', $infos[0]);

  $url = '../'.$infos[0].' Class/public '.str_replace('::', '--', $name) . '.md' ;
  $url = str_replace(' ', '%20', $url);

  return "[".$name."](".$url.")";
}


function createMarkdownContent (array $entry)
{

	// Header

	$md =
"## ".
$entry['visibility'].
(($entry['static']) ? " static " : " "). 
$entry['class']."::".$entry['name'].     "

### Description    

".computeCleverSpec($entry['static'], $entry['visibility'], $entry['class'],$entry['name'],(isset($entry['input'])) ? $entry['input'] : null)."

".$entry['description']."    
";

	// Input


if (isset($entry['input'])) :	foreach ($entry['input'] as $key => $value ) :
$md .= "

##### **".$key.":** *".$value['type']."*   
".((isset($value['text']))?$value['text']:"")."    

";
	endforeach;
endif;

	
	// Return Value

	$md .= "

### Return value:   

".$entry['return']."

";

	// Related methods

	if(!empty($entry['related'])) :

		$md .=
"
---------------------------------------

### Related method(s)      

";

		foreach ($entry['related'] as $value) {

      if ($value === $entry['class'].'::'.$entry['name']) : continue; endif;

$md .= "* ".cleverRelated($value)."    
";
		}

	endif;

	if(!empty($entry['examples'])) :

		$md .=
"
---------------------------------------

### Examples and explanation

";

	foreach ($entry['examples'] as $key => $value) {
$md .= "* **[".$key."](".$value.")**    
";
	}

	endif;

	return $md;

}
