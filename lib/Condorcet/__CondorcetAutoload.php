<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.93

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet ;

// Self Autoload function coming after and as a fallback of composer or other framework PSR autoload implementation. Composer or framework autoload will alway be will be preferred to that custom function. Exept for algorithms class.
spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');

    if (substr($className,0,9) === 'Condorcet') :
        if ($lastNsPos = strripos($className, '\\')) {
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, substr($className, 0, $lastNsPos)) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, substr($className, $lastNsPos + 1)) . '.php';

        require substr_replace($fileName, __DIR__ . DIRECTORY_SEPARATOR, 0, 10);
    else :
        return false ;
    endif;
});


// Include Algorithms - ALGO class do not meet the autloading PSR standard. But that does not disturb you.
if (!class_exists('\\Condorcet\Condorcet_Basic', false)) {
    require_once __DIR__ . DIRECTORY_SEPARATOR."algorithms".DIRECTORY_SEPARATOR."Condorcet_Basic.algo.core.php";

    foreach (glob( __DIR__ . DIRECTORY_SEPARATOR."algorithms".DIRECTORY_SEPARATOR."*.algo.php" ) as $Condorcet_filename)
    {
        include_once $Condorcet_filename ;
    }
}
