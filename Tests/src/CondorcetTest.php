<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;
use CondorcetPHP\Condorcet\Throwable\AlgorithmException;
use PHPUnit\Framework\Attributes\BackupStaticProperties;
use PHPUnit\Framework\TestCase;

class CondorcetTest extends TestCase
{
    public function testgetVersion(): void
    {
        $this->assertSame(Condorcet::VERSION, CONDORCET::getVersion());
        $this->assertMatchesRegularExpression('/^[1-9]+\.[0-9]+$/', CONDORCET::getVersion(true));
    }

    public function testAddExistingMethod(): void
    {
        $algoClassPath = Condorcet::getDefaultMethod();

        $this->assertEquals($algoClassPath, Condorcet::getMethodClass($algoClassPath));

        $this->assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testBadClassMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage("The voting algorithm is not available: no class found for 'sjskkdlkkzksh'");

        Condorcet::addMethod('sjskkdlkkzksh');
    }

    public function testAuthMethod(): void
    {
        $this->assertFalse(Condorcet::isAuthMethod('skzljdpmzk'));
        $this->assertNull(Condorcet::getMethodClass('skzljdpmzk'));
        $this->assertSame(\CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning::class, Condorcet::getMethodClass('Schulze Winning'));
    }

    #[BackupStaticProperties(true)]
    public function testAddMethod(): never
    {
        $algoClassPath = CondorcetTest_ValidAlgorithmName::class;

        $this->assertTrue(Condorcet::addMethod($algoClassPath));

        $this->assertEquals($algoClassPath, Condorcet::getMethodClass($algoClassPath));

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
        $this->assertFalse(Condorcet::setDefaultMethod('dgfbdwcd'));
    }

    public function testEmptyMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: no method name given');

        Condorcet::isAuthMethod('');
    }

    public function testMethodAlias(): void
    {
        $this->assertSame(
            \CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::class,
            Condorcet::getMethodClass('kemeny–Young')
        );

        $this->assertSame(
            \CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::class,
            Condorcet::getMethodClass('Maximum likelihood Method')
        );
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

        $result = [0=>1, 1=>2, 2=>3]; // Candidate must be valid candidates

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

        $result = [0=>0, 1=> [1, 2], 2=> 3]; // Candidate must be valid internal candidate key.

        $this->Result = $result;
    }
}
