<?php

declare(strict_types=1);

namespace Tests;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\Generate;

arch()->preset()->php()->ignoring(Generate::class);
arch()->preset()->security();
// arch()->preset()->strict()->ignoring('to be final');