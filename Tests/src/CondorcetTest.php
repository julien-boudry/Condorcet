<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\{Candidate, Condorcet, CondorcetUtil, Election, Result, Vote, VoteConstraint};
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Throwable\AlgorithmException;

use PHPUnit\Framework\TestCase;

class CondorcetTest extends TestCase
{
    public function testgetVersion(): void
    {
        self::assertSame(Condorcet::VERSION, CONDORCET::getVersion());
        self::assertMatchesRegularExpression('/^[1-9]+\.[0-9]+$/', CONDORCET::getVersion(true));
    }

    public function testAddExistingMethod(): void
    {
        $algoClassPath = Condorcet::getDefaultMethod();

        self::assertEquals($algoClassPath, Condorcet::getMethodClass($algoClassPath));

        self::assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testBadClassMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage("The voting algorithm is not available: no class found for 'sjskkdlkkzksh'");

        Condorcet::addMethod('sjskkdlkkzksh');
    }

    public function testAuthMethod(): void
    {
        self::assertFalse(Condorcet::isAuthMethod('skzljdpmzk'));
        self::assertNull(Condorcet::getMethodClass('skzljdpmzk'));
        self::assertSame(\CondorcetPHP\Condorcet\Algo\Methods\Schulze\SchulzeWinning::class, Condorcet::getMethodClass('Schulze Winning'));
    }

    /**
      * @preserveGlobalState disabled
      * @backupStaticAttributes disabled
      * @runInSeparateProcess
      */
    public function testAddMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: the given class is using an existing alias');

        $algoClassPath = CondorcetTest_ValidAlgorithmName::class;

        self::assertTrue(Condorcet::addMethod($algoClassPath));

        self::assertEquals($algoClassPath, Condorcet::getMethodClass($algoClassPath));

        // Try to add existing alias
        $algoClassPath = CondorcetTest_DuplicateAlgorithmAlias::class;

        self::assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testAddUnvalidMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: the given class is not correct');

        $algoClassPath = CondorcetTest_UnvalidAlgorithmName::class;

        self::assertFalse(Condorcet::addMethod($algoClassPath));

        self::assertSame(
            CondorcetTest_UnvalidAlgorithmName::class,
            Condorcet::getMethodClass('FirstMethodName')
        );
    }

    public function testUnvalidDefaultMethod(): void
    {
        self::assertFalse(Condorcet::setDefaultMethod('dgfbdwcd'));
    }

    public function testEmptyMethod(): never
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionMessage('The voting algorithm is not available: no method name given');

        Condorcet::isAuthMethod('');
    }

    public function testMethodAlias(): void
    {
        self::assertSame(
            \CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung::class,
            Condorcet::getMethodClass('kemeny–Young')
        );

        self::assertSame(
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
        if ($this->_Result !== null) {
            return $this->_Result;
        }

        //////

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->_Result;
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
        $this->_selfElection->get()->getPairwise();

        $result = [0=>1, 1=>2, 2=>3]; // Candidate must be valid candidates

        $this->_Result = $this->createResult($result);
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
        if ($this->_Result !== null) {
            return $this->_Result;
        }

        //////

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->_Result;
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
        $this->_selfElection->getPairwise();

        $result = [0=>0, 1=> [1, 2], 2=> 3]; // Candidate must be valid internal candidate key.

        $this->_Result = $result;
    }
}
