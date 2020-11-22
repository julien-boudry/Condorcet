<?php
declare(strict_types=1);

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\Generate as GenerateCondorcetDocumentationGenerator;

  require_once __DIR__.str_replace('/',DIRECTORY_SEPARATOR,'/../vendor/../vendor/autoload.php');

  // Build command

  $path = substr(__DIR__, 0, strlen(__DIR__) - 4);
  $path .= DIRECTORY_SEPARATOR.'Documentation';

  // Clear folder

  function rrmdir(string $dir, string $path) : void {
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

  new GenerateCondorcetDocumentationGenerator($path);
