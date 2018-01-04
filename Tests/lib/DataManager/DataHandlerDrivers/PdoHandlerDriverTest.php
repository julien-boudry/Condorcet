<?php
declare(strict_types=1);
namespace Condorcet\DataManager\DataHandlerDrivers;

use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Vote;
use Condorcet\DataManager\ArrayManager;
use Condorcet\DataManager\DataHandlerDrivers\PdoHandlerDriver;
use PHPUnit\Framework\TestCase;

/**
 * @covers Condorcet\DataManager\DataHandlerDrivers\PdoHandlerDriver
 * @covers Condorcet\DataManager\Datamanager
 * @covers Condorcet\DataManager\VotesManager
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PdoHandlerDriverTest extends TestCase
{
    protected function getDriverReady ()
    {
        $pdoObject = new \PDO ('sqlite::memory:','','',[\PDO::ATTR_PERSISTENT => false]);

        return new PdoHandlerDriver ($pdoObject, true);  
    }

    public function testManyVoteManipulation()
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionInMemory = new Election;
        $electionWithDb->setExternalDataHandler($this->getDriverReady());

        // Run Test

        $electionWithDb->parseCandidates('A;B;C;D;E');
        $electionInMemory->parseCandidates('A;B;C;D;E');

        $votes =   'A > C > B > E * 5
                    A > D > E > C * 5
                    B > E > D > A * 8
                    C > A > B > E * 3
                    C > A > E > B * 7
                    C > B > A > D * 2
                    D > C > E > B * 7
                    E > B > A > D * 8';

        $electionWithDb->parseVotes($votes);
        $electionInMemory->parseVotes($votes);

        self::assertSame($electionInMemory->countVotes(),$electionWithDb->countVotes());
        self::assertSame($electionInMemory->getVotesListAsString(),$electionWithDb->getVotesListAsString());

        self::assertEquals((string) $electionInMemory->getWinner('Ranked Pairs Winning'),(string) $electionWithDb->getWinner('Ranked Pairs Winning'));
        self::assertEquals($electionInMemory->getWinner(),$electionWithDb->getWinner());


        $votes = 'A > B > C > E * 58';

        $electionWithDb->parseVotes($votes);
        $electionInMemory->parseVotes($votes);

        self::assertEquals('A',$electionWithDb->getWinner());
        self::assertEquals((string) $electionInMemory->getWinner(),(string) $electionWithDb->getWinner());

        self::assertSame($electionInMemory->countVotes(),$electionWithDb->countVotes());
        self::assertSame($electionInMemory->getVotesListAsString(),$electionWithDb->getVotesListAsString());

        $browseVotes = function (Election $elec) : int {
            $c = 0;
            foreach ($elec->getVotesManager() as $value) :
                $c++;
            endforeach;
            return $c;
        };

        self::assertSame($browseVotes($electionInMemory),$browseVotes($electionWithDb));
    }

}
