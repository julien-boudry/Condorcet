<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;

use PHPUnit\Framework\TestCase;

class CondorcetTest extends TestCase
{
    public function testgetVersion () : void
    {
        self::assertSame(Condorcet::VERSION,CONDORCET::getVersion());
        self::assertRegExp('/^[1-9]+\.[0-9]+$/',CONDORCET::getVersion(true));
    }

    public function testAddExistingMethod () : void
    {
        $algoClassPath = Condorcet::getDefaultMethod();

        self::assertEquals($algoClassPath,Condorcet::getMethodClass($algoClassPath));

        self::assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testBadClassMethod () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(9);

        Condorcet::addMethod('sjskkdlkkzksh');
    }

    public function testAuthMethod () : void
    {
        self::assertFalse(Condorcet::isAuthMethod('skzljdpmzk'));
        self::assertNull(Condorcet::getMethodClass('skzljdpmzk'));
        self::assertSame(Algo\Methods\Schulze\SchulzeWinning::class,Condorcet::getMethodClass('Schulze Winning'));
    }

    public function testAddMethod () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(25);

        $algoClassPath = CondorcetTest_ValidAlgorithmName::class;

        self::assertTrue(Condorcet::addMethod($algoClassPath));

        self::assertEquals($algoClassPath,Condorcet::getMethodClass($algoClassPath));

        // Try to add existing alias
        $algoClassPath = CondorcetTest_DuplicateAlgorithmAlias::class;

        self::assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testAddUnvalidMethod () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(10);

        $algoClassPath = CondorcetTest_UnvalidAlgorithmName::class;

        self::assertFalse(Condorcet::addMethod($algoClassPath));

        self::assertSame(
            CondorcetTest_UnvalidAlgorithmName::class,
            Condorcet::getMethodClass('FirstMethodName')
        );
    }

    public function testUnvalidDefaultMethod () : void
    {
        self::assertFalse(Condorcet::setDefaultMethod('dgfbdwcd'));
    }

    public function testEmptyMethod () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(8);

        Condorcet::isAuthMethod('');
    }

    public function testMethodAlias () : void
    {
        self::assertSame(
            Algo\Methods\KemenyYoung\KemenyYoung::class,
            Condorcet::getMethodClass('kemeny–Young')
        );

        self::assertSame(
            Algo\Methods\KemenyYoung\KemenyYoung::class,
            Condorcet::getMethodClass('Maximum likelihood Method')
        );
    }

}


class CondorcetTest_ValidAlgorithmName extends Method implements MethodInterface
{
    const METHOD_NAME = ['FirstMethodName','Alias1','Alias_2','Alias 3'];


    // Get the Result object
    public function getResult ($options = null) : Result
    {
        // Cache
        if ( $this->_Result !== null )
        {
            return $this->_Result;
        }

            //////

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->_Result;
    }


    // Compute the Stats
    protected function getStats () : array
    {
        return []; // You are free to do all you wants. Must be an array.;
    }



/////////// COMPUTE ///////////


    //:: ALGORITHM. :://

    protected function makeRanking ()
    {
        $this->_selfElection->getPairwise();

        $result = [0=>$CandidateX, 1=> [$CandidateY,$CandidateZ], 2=> $CandidateR]; // Candidate must be valid internal candidate key.

        $this->_Result = $this->createResult($result);
    }
}

class CondorcetTest_DuplicateAlgorithmAlias extends CondorcetTest_ValidAlgorithmName implements MethodInterface
{
    const METHOD_NAME = ['SecondMethodName','Alias_2'];
}


class CondorcetTest_UnvalidAlgorithmName
{
    const METHOD_NAME = ['FirstMethodName','Alias1','Alias_2','Alias 3'];


    // Get the Result object
    public function getResult ($options = null) : Result
    {
        // Cache
        if ( $this->_Result !== null )
        {
            return $this->_Result;
        }

            //////

        // Ranking calculation
        $this->makeRanking();

        // Return
        return $this->_Result;
    }


    // Compute the Stats
    protected function getStats () : array
    {
        return []; // You are free to do all you wants. Must be an array.;
    }



/////////// COMPUTE ///////////


    //:: ALGORITHM. :://

    protected function makeRanking ()
    {
       $this->_selfElection->getPairwise();

        $result = [0=>$CandidateX, 1=> [$CandidateY,$CandidateZ], 2=> $CandidateR]; // Candidate must be valid internal candidate key.

        $this->_Result = $this->createResult($result);
    }
}