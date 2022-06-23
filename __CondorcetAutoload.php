<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

if (version_compare($condorcet_minimal_php_version = substr(str_replace('^', '', json_decode(json: file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'composer.json'), associative: true, flags: \JSON_THROW_ON_ERROR)['require']['php']), 1, 3), \PHP_VERSION, '>')) {
    trigger_error(
        'Condorcet PHP requires a PHP version greater than or equal to '.$condorcet_minimal_php_version.'. Your current version is '.\PHP_VERSION.'.',
        E_USER_ERROR
    );
}

// Self Autoload function coming after and as a fallback of composer or other framework PSR autoload implementation. Composer or framework autoload will alway be will be preferred to that custom function.
spl_autoload_register(function (string $class): void {

    // project-specific namespace prefix
    $prefix = 'CondorcetPHP\Condorcet\\';


    // does the class use the namespace prefix?
    $len = \strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
