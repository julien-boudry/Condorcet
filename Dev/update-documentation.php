<?php

declare(strict_types=1);

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\Generate;

require_once __DIR__.str_replace('/', \DIRECTORY_SEPARATOR, '/../vendor/../vendor/autoload.php');

  // Build command

  $path = mb_substr(__DIR__, 0, mb_strlen(__DIR__) - 4);
  $path .= \DIRECTORY_SEPARATOR.'Documentation';

  // Clear folder

  function rrmdir(string $dir, string $path): void
  {
      if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
              if ($object !== '.' && $object !== '..') {
                  if (filetype($dir.\DIRECTORY_SEPARATOR.$object) === 'dir') {
                      rrmdir($dir.\DIRECTORY_SEPARATOR.$object, $path);
                  } else {
                      unlink($dir.\DIRECTORY_SEPARATOR.$object);
                  }
              }
          }
          reset($objects);
          if ($dir !== $path) {
              rmdir($dir);
          }
      }
  }

  rrmdir($path, $path);

  // Execute command

  new Generate($path);
