<?php
/*
	Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.91

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet ;

spl_autoload_register(function ($className) {

	$className = ltrim($className, '\\');

	if (substr($className,0,9) !== 'Condorcet') :
		return false;
	else :
		require_once __DIR__ . DIRECTORY_SEPARATOR . substr($className,10) . ".php";
	endif;

});


// Include Algorithms
if (!class_exists('\\Condorcet\Condorcet_Basic', false)) {
	foreach (glob( __DIR__ . DIRECTORY_SEPARATOR."algorithms".DIRECTORY_SEPARATOR."*.algo.php" ) as $Condorcet_filename)
	{
		include_once $Condorcet_filename ;
	}
}