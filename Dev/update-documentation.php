<?php
declare(strict_types=1);

    // Build command

    $call = str_replace('/', DIRECTORY_SEPARATOR, __DIR__.'/../vendor/bin/condorcet-doc');
    $path = substr(__DIR__, 0, strlen(__DIR__) - 4);
    $path .= DIRECTORY_SEPARATOR.'Documentation';

    $cmd = $call.' "'.$path.'"';

    // Clear folder

    function rrmdir($dir, $path) {
       if (is_dir($dir)) :
         $objects = scandir($dir);
         foreach ($objects as $object) :
           if ($object != "." && $object != ".." && $object !== 'doc.yaml') :
             if (filetype($dir.DIRECTORY_SEPARATOR.$object) == "dir") :
                rrmdir($dir.DIRECTORY_SEPARATOR.$object,$path);
            else :
                 unlink($dir.DIRECTORY_SEPARATOR.$object);
            endif;
           endif;
         endforeach;
         reset($objects);
        if ($dir !== $path) :
            rmdir($dir);
        endif;
       endif;
    }

    rrmdir($path,$path);

    // Execute command

    shell_exec($cmd);
