<?php
// Linux: php -dzend_extension=opcache -dopcache.enable_cli=1 -dopcache.jit_buffer_size=100M -dopcache.jit=tracing Dev/bugs/JitBug.php
// Windows: php -d zend_extension=opcache -d opcache.enable_cli=1 -d opcache.jit_buffer_size=100M -d opcache.jit=tracing Dev/bugs/JitBug.php

# Notes
// Not happening on 8.1
// Bug only with jit tracing mode (working with jit function) (test on PHP 8.0.10 NTS on x64)
// Working with or without opcache

# Bugs
// PHP Fatal error:  Uncaught TypeError: CondorcetPHP\Condorcet\Result::__construct(): Argument #6 ($seats) must be of type ?int, CondorcetPHP\Condorcet\Election given, called in C:\dev_scripts\Condorcet\lib\Algo\Method.php on line 84 and defined in C:\dev_scripts\Condorcet\lib\Result.php:89
// Stack trace:
// #0 C:\dev_scripts\Condorcet\lib\Algo\Method.php(84): CondorcetPHP\Condorcet\Result->__construct()
// #1 C:\dev_scripts\Condorcet\lib\Algo\Methods\STV\SingleTransferableVote.php(91): CondorcetPHP\Condorcet\Algo\Method->createResult()
// #2 C:\dev_scripts\Condorcet\lib\Algo\Method.php(63): CondorcetPHP\Condorcet\Algo\Methods\STV\SingleTransferableVote->compute()
// #3 C:\dev_scripts\Condorcet\lib\ElectionProcess\ResultsProcess.php(80): CondorcetPHP\Condorcet\Algo\Method->getResult()
// #4 C:\dev_scripts\Condorcet\Dev\bugs\JitBug.php(24): CondorcetPHP\Condorcet\Election->getResult()
// #5 {main}
//   thrown in C:\dev_scripts\Condorcet\lib\Result.php on line 89

# Test code

namespace CondorcetPHP\Condorcet;

require_once __DIR__.'/../../__CondorcetAutoload.php';

for ($i=1; $i <= 4000; $i++) :

    # With Condorcet
        $election = new Election;

        $election->parseCandidates('A;B;C');

        $election->addVote('A>B>C');

        $election->getResult('Schulze');
        $election->getResult('STV');

    # Tentative to reproduce (impossible...)
        // $a = new A (new Bar);
        // $b = new B (new Bar);

endfor;


# Tentative to reproduce (impossible...)

class Foo {
    function __construct (public string $fromMethod, public string $byClass, public Bar $election, public array $result, public $stats, public ?int $seats = null, public array $methodOptions = [])
    {
    }
}

class Bar  {}

abstract class Base {

    public const IS_PROPORTIONAL = false;

    public Foo $foo;

    public function __construct(public Bar $barInstance)
    {
        $this->foo = new Foo (
            fromMethod: 'test',
            byClass: $this::class,
            election: $this->barInstance,
            result: [],
            stats: [],
            seats: (static::IS_PROPORTIONAL) ? $this->getSeats(): null,
            methodOptions: []
        );
    }

    public function getSeats() : int {
        return 6;
    }
}

class A extends Base {

    public const IS_PROPORTIONAL = false;
}

class B extends Base {

    public const IS_PROPORTIONAL = true;
}