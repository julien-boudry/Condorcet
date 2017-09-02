<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Condorcet\Condorcet
 * @runTestsInSeparateProcesses
 */
class CondorcetTest extends TestCase
{
    public function testaddExistingMethod ()
    {
        $algoClassPath = Condorcet::getDefaultMethod();

        $this->assertEquals($algoClassPath,Condorcet::isAuthMethod($algoClassPath));

        $this->assertFalse(Condorcet::addMethod($algoClassPath));
    }

    public function testaddValidMethod ()
    {
        $algoClassPath = 'Condorcet\\CondorcetTest_ValidAlgorithmName';

        $this->assertTrue(Condorcet::addMethod($algoClassPath));

        $this->assertEquals($algoClassPath,Condorcet::isAuthMethod($algoClassPath));
    }

    /**
      * @expectedException Condorcet\CondorcetException
      * @expectedExceptionCode 10
      */
    public function testaddUnvalidMethod ()
    {
        $algoClassPath = 'Condorcet\\CondorcetTest_UnvalidAlgorithmName';

        $this->assertFalse(Condorcet::addMethod($algoClassPath));
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
        $myPairwise = $this->_selfElection->getPairwise(false);

        $result = [0=>$CandidateX, 1=> [$CandidateY,$CandidateZ], 2=> $CandidateR]; // Candidate must be valid internal candidate key.

        $this->_Result = $this->createResult($result);
    }
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
        $myPairwise = $this->_selfElection->getPairwise(false);

        $result = [0=>$CandidateX, 1=> [$CandidateY,$CandidateZ], 2=> $CandidateR]; // Candidate must be valid internal candidate key.

        $this->_Result = $this->createResult($result);
    }
}