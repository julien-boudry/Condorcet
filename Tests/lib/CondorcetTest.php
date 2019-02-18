<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use CondorcetPHP\Condorcet\Algo\Method;
use CondorcetPHP\Condorcet\Algo\MethodInterface;

use PHPUnit\Framework\TestCase;

class CondorcetTest extends TestCase
{
    public function testgetVersion ()
    {
        self::assertSame(Condorcet::VERSION,CONDORCET::getVersion());
        self::assertRegExp('/^[1-9]+\.[1-9]+$/',CONDORCET::getVersion('MAJOR'));
    }

    public function testAddExistingMethod ()
    {
        $algoClassPath = Condorcet::getDefaultMethod();

        self::assertEquals($algoClassPath,Condorcet::isAuthMethod($algoClassPath));

        self::assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testBadClassMethod ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(9);

        Condorcet::addMethod('sjskkdlkkzksh');
    }

    public function testAuthMethod ()
    {
        self::assertFalse(Condorcet::isAuthMethod('skzljdpmzk'));
        self::assertSame(__NAMESPACE__.'\Algo\Methods\SchulzeWinning',Condorcet::isAuthMethod('Schulze Winning'));
    }

    public function testAddMethod ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(25);

        $algoClassPath = __NAMESPACE__.'\\CondorcetTest_ValidAlgorithmName';

        self::assertTrue(Condorcet::addMethod($algoClassPath));

        self::assertEquals($algoClassPath,Condorcet::isAuthMethod($algoClassPath));

        // Try to add existing alias
        $algoClassPath = __NAMESPACE__.'\\CondorcetTest_DuplicateAlgorithmAlias';

        self::assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testAddUnvalidMethod ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(10);

        $algoClassPath = __NAMESPACE__.'\CondorcetTest_UnvalidAlgorithmName';

        self::assertFalse(Condorcet::addMethod($algoClassPath));

        self::assertSame(
            __NAMESPACE__.'\\CondorcetTest_UnvalidAlgorithmName',
            Condorcet::isAuthMethod('FirstMethodName')
        );
    }

    public function testUnvalidDefaultMethod ()
    {
        self::assertFalse(Condorcet::setDefaultMethod('dgfbdwcd'));
    }

    public function testEmptyMethod ()
    {
        $this->expectException(\CondorcetPHP\Condorcet\CondorcetException::class);
        $this->expectExceptionCode(8);

        Condorcet::isAuthMethod('');
    }

    public function testMethodAlias ()
    {
        self::assertSame(
            __NAMESPACE__.'\\Algo\\Methods\\KemenyYoung',
            Condorcet::isAuthMethod('kemeny–Young')
        );

        self::assertSame(
            __NAMESPACE__.'\\Algo\\Methods\\KemenyYoung',
            Condorcet::isAuthMethod('Maximum likelihood Method')
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
        $this->_selfElection->getPairwise(false);

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
       $this->_selfElection->getPairwise(false);

        $result = [0=>$CandidateX, 1=> [$CandidateY,$CandidateZ], 2=> $CandidateR]; // Candidate must be valid internal candidate key.

        $this->_Result = $this->createResult($result);
    }
}