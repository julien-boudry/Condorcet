<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

// Self Autoload function coming after and as a fallback of composer or other framework PSR autoload implementation. Composer or framework autoload will alway be will be preferred to that custom function. Exept for algorithms class.
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Condorcet\\';


    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) :
        // no, move to the next registered autoloader
        return false;
    endif;

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) :
        require $file;
    else :
        return false;
    endif;
});
