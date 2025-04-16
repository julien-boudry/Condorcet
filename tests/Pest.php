<?php declare(strict_types=1);


use CondorcetPHP\Condorcet\Condorcet;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

uses(Tests\CondorcetTestCase::class)->in('Examples');
pest()->extend(Tests\AlgoTestCase::class)->in('src/Algo');
pest()->extend(Tests\src\Console\ConsoleTestCase::class)->in('src/Console');
pest()->extend(Tests\src\Tools\Randomizers\RandomizerTestCase::class)->in('src/Tools/Randomizers');


/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

// expect()->extend('toBeOne', function () {
//     return $this->toBe(1);
// });

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function getMethodList(): array
{
    $methods = Condorcet::getAuthMethods(withNonDeterministicMethods: false);
    array_walk($methods, static fn(&$m): array => $m = [$m]);

    return $methods;
}

function hasPDO(): bool
{
    return \extension_loaded('pdo_sqlite');
}
