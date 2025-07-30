<?php declare(strict_types=1);


use CondorcetPHP\Condorcet\{Candidate, Condorcet, Result};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\{EmptyStats, StatsInterface};
use CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented;

test('get version', function (): void {
    expect(Condorcet::getVersion())->toBe(Condorcet::VERSION);
    expect(Condorcet::getVersion(true))->toMatch('/^[1-9]+\.[0-9]+$/');
});

test('add existing method', function (): void {
    $algoClassPath = Condorcet::getDefaultMethod();

    expect(Condorcet::getMethodClass($algoClassPath))->toEqual($algoClassPath);

    expect(Condorcet::addMethod($algoClassPath))->toBeFalse();
});

test('bad class method', function (): void {
    $this->expectException(VotingMethodIsNotImplemented::class);
    $this->expectExceptionMessage("The voting algorithm is not available: no class found for 'sjskkdlkkzksh'");

    Condorcet::addMethod('sjskkdlkkzksh');
});

test('auth method', function (): void {
    expect(Condorcet::isAuthMethod('skzljdpmzk'))->toBeFalse();
    expect(Condorcet::getMethodClass('skzljdpmzk'))->toBeNull();
    expect(Condorcet::getMethodClass('Schulze Winning'))->toBe(CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning::class);
});

test('get auth methods', function (): void {
    expect(\count(Condorcet::getAuthMethods()))
        ->toBe(\count(Condorcet::getAuthMethods(true)) - 1)
        ->toBeGreaterThan(\count(Condorcet::getAuthMethods(withNonDeterministicMethods: false)));
});

test('add method', function (): void {
    $algoClassPath = CondorcetTest_ValidAlgorithmName::class;

    expect(Condorcet::addMethod($algoClassPath))->toBeTrue();

    expect(Condorcet::getMethodClass($algoClassPath))->toEqual($algoClassPath);

    // Try to add existing alias
    $algoClassPath = CondorcetTest_DuplicateAlgorithmAlias::class;

    $this->expectException(VotingMethodIsNotImplemented::class);
    $this->expectExceptionMessage('The voting algorithm is not available: the given class is using an existing alias');

    Condorcet::addMethod($algoClassPath);
});

test('add unvalid method', function (): void {
    $algoClassPath = CondorcetTest_UnvalidAlgorithmName::class;

    $this->expectException(VotingMethodIsNotImplemented::class);
    $this->expectExceptionMessage('The voting algorithm is not available: the given class is not correct');

    Condorcet::addMethod($algoClassPath);
});

test('unvalid default method', function (): void {
    expect(Condorcet::setDefaultMethod('dgfbdwcd'))->toBeFalse();
});

test('empty method', function (): void {
    $this->expectException(VotingMethodIsNotImplemented::class);
    $this->expectExceptionMessage('The voting algorithm is not available: no method name given');

    Condorcet::isAuthMethod('');
});

test('method alias', function (): void {
    expect(Condorcet::getMethodClass('kemeny–Young'))->toBe(CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::class);

    expect(Condorcet::getMethodClass('Maximum likelihood Method'))->toBe(CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::class);
});

class CondorcetTest_ValidAlgorithmName extends Method implements MethodInterface
{
    public const array METHOD_NAME = ['FirstMethodName', 'Alias1', 'Alias_2', 'Alias 3'];

    // Get the Result object
    #[Override]
    public function getResult($options = null): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        //////

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->Result;
    }

    // Compute the Stats
    protected function getStats(): StatsInterface
    {
        return new EmptyStats;
    }



    /////////// COMPUTE ///////////


    //:: ALGORITHM. :://

    protected function makeRanking(): void
    {
        $election = $this->getElection();

        $this->Result = $this->createResult(\array_slice(array_map(fn(Candidate $e): int => $election->getCandidateKey($e), $this->getElection()->getCandidatesList()), 0, 3));
    }
}

class CondorcetTest_DuplicateAlgorithmAlias extends CondorcetTest_ValidAlgorithmName implements MethodInterface
{
    public const array METHOD_NAME = ['SecondMethodName', 'Alias_2'];
}


class CondorcetTest_UnvalidAlgorithmName
{
    public Result $Result;
    public const array METHOD_NAME = ['FirstMethodName', 'Alias1', 'Alias_2', 'Alias 3'];

    // Get the Result object
    public function getResult($options = null): Result
    {
        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->Result;
    }

    // Compute the Stats
    protected function getStats(): array
    {
        return []; // You are free to do all you wants. Must be an array.;
    }



    /////////// COMPUTE ///////////


    //:: ALGORITHM. :://

    protected function makeRanking(): void
    {
        $result = [0 => 0, 1 => [1, 2], 2 => 3]; // Candidate must be valid internal candidate key.
    }
}
