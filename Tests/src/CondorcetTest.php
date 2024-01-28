<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Condorcet, Result};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Throwable\AlgorithmException;
use PHPUnit\Framework\Attributes\BackupStaticProperties;
use PHPUnit\Framework\TestCase;

class CondorcetTest extends TestCase
{
    public function testgetVersion(): void
    {
        expect(CONDORCET::getVersion())->toBe(Condorcet::VERSION);
        expect(CONDORCET::getVersion(true))->toMatch('/^[1-9]+\.[0-9]+$/');
    }

    public function testAddExistingMethod(): void
    {
        $algoClassPath = Condorcet::getDefaultMethod();

        expect(Condorcet::getMethodClass($algoClassPath))->toEqual($algoClassPath);

        expect(Condorcet::addMethod($algoClassPath))->toBeFalse();
    }

    public function testBadClassMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage("The voting algorithm is not available: no class found for 'sjskkdlkkzksh'");

        Condorcet::addMethod('sjskkdlkkzksh');
    }

    public function testAuthMethod(): void
    {
        expect(Condorcet::isAuthMethod('skzljdpmzk'))->toBeFalse();
        expect(Condorcet::getMethodClass('skzljdpmzk'))->toBeNull();
        expect(Condorcet::getMethodClass('Schulze Winning'))->toBe(\CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning::class);
    }

    public function testGetAuthMethods(): void
    {
        expect(\count(Condorcet::getAuthMethods()))
            ->toBe(\count(Condorcet::getAuthMethods(true)) - 1)
            ->toBeGreaterThan(\count(Condorcet::getAuthMethods(withNonDeterministicMethods: false)));
    }

    #[BackupStaticProperties(true)]
    public function testAddMethod(): never
    {
        $algoClassPath = CondorcetTest_ValidAlgorithmName::class;

        expect(Condorcet::addMethod($algoClassPath))->toBeTrue();

        expect(Condorcet::getMethodClass($algoClassPath))->toEqual($algoClassPath);

        // Try to add existing alias
        $algoClassPath = CondorcetTest_DuplicateAlgorithmAlias::class;

        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: the given class is using an existing alias');

        Condorcet::addMethod($algoClassPath);
    }

    public function testAddUnvalidMethod(): never
    {
        $algoClassPath = CondorcetTest_UnvalidAlgorithmName::class;

        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: the given class is not correct');

        Condorcet::addMethod($algoClassPath);
    }

    public function testUnvalidDefaultMethod(): void
    {
        expect(Condorcet::setDefaultMethod('dgfbdwcd'))->toBeFalse();
    }

    public function testEmptyMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: no method name given');

        Condorcet::isAuthMethod('');
    }

    public function testMethodAlias(): void
    {
        expect(Condorcet::getMethodClass('kemeny–Young'))->toBe(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::class);

        expect(Condorcet::getMethodClass('Maximum likelihood Method'))->toBe(\CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::class);
    }
}


class CondorcetTest_ValidAlgorithmName extends Method implements MethodInterface
{
    public const METHOD_NAME = ['FirstMethodName', 'Alias1', 'Alias_2', 'Alias 3'];


    // Get the Result object
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
    protected function getStats(): array
    {
        return []; // You are free to do all you wants. Must be an array.;
    }



    /////////// COMPUTE ///////////


    //:: ALGORITHM. :://

    protected function makeRanking(): void
    {
        $this->selfElection->get()->getPairwise();

        $result = [0 => 1, 1 => 2, 2 => 3]; // Candidate must be valid candidates

        $this->Result = $this->createResult($result);
    }
}

class CondorcetTest_DuplicateAlgorithmAlias extends CondorcetTest_ValidAlgorithmName implements MethodInterface
{
    public const METHOD_NAME = ['SecondMethodName', 'Alias_2'];
}


class CondorcetTest_UnvalidAlgorithmName
{
    public const METHOD_NAME = ['FirstMethodName', 'Alias1', 'Alias_2', 'Alias 3'];


    // Get the Result object
    public function getResult($options = null): Result
    {
        // Cache
        if ($thisResult !== null) {
            return $this->Result;
        }

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
        $this->selfElection->getPairwise();

        $result = [0 => 0, 1 => [1, 2], 2 => 3]; // Candidate must be valid internal candidate key.

        $this->Result = $result;
    }
}
