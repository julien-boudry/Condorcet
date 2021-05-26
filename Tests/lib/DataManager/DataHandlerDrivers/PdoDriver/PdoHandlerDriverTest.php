<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\DataManager\DataHandlerDrivers\PdoDriver;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\DataManager\ArrayManager;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver;
use PHPUnit\Framework\TestCase;

/**
 * @preserveGlobalState disabled
 * @backupStaticAttributes disabled
 */
class PdoHandlerDriverTest extends TestCase
{
    protected function getPDO () : \PDO
    {
        return new \PDO ('sqlite::memory:','','',[\PDO::ATTR_PERSISTENT => false]);
    }

    protected function hashVotesList (Election $elec) : string {
            $c = 0;
            $voteCompil = '';
            foreach ($elec->getVotesManager() as $oneVote) :
                $c++;
                $voteCompil .= (string) $oneVote;
            endforeach;

            return $c.'||'.hash('md5',$voteCompil);
    }


    public function testManyVoteManipulation() : void
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionInMemory = new Election;
        $electionWithDb->setExternalDataHandler($handlerDriver = new PdoHandlerDriver ($this->getPDO(),true));

        // Run Test

        $electionWithDb->parseCandidates('A;B;C;D;E');
        $electionInMemory->parseCandidates('A;B;C;D;E');

        // 45 Votes
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

        self::assertSame(   $electionWithDb->countVotes(),
                            $handlerDriver->countEntities() + $electionWithDb->getVotesManager()->getContainerSize() );

        self::assertSame($electionInMemory->countVotes(),$electionWithDb->countVotes());
        self::assertSame($electionInMemory->getVotesListAsString(),$electionWithDb->getVotesListAsString());
        self::assertSame($this->hashVotesList($electionInMemory),$this->hashVotesList($electionWithDb));

        self::assertEquals($electionInMemory->getPairwise()->getExplicitPairwise(),$electionWithDb->getPairwise()->getExplicitPairwise());
        self::assertEquals((string) $electionInMemory->getWinner('Ranked Pairs Winning'),(string) $electionWithDb->getWinner('Ranked Pairs Winning'));
        self::assertEquals((string) $electionInMemory->getWinner(),(string) $electionWithDb->getWinner());
        self::assertEquals((string) $electionInMemory->getCondorcetWinner(),(string) $electionWithDb->getCondorcetWinner());


        // 58 Votes
        $votes = 'A > B > C > E * 58';

        $electionWithDb->parseVotes($votes);
        $electionInMemory->parseVotes($votes);

        self::assertSame(58 % ArrayManager::$MaxContainerLength,$electionWithDb->getVotesManager()->getContainerSize());
        self::assertSame(   $electionWithDb->countVotes(),
                            $handlerDriver->countEntities() + $electionWithDb->getVotesManager()->getContainerSize() );

        self::assertEquals('A',$electionWithDb->getWinner());
        self::assertEquals((string) $electionInMemory->getWinner(),(string) $electionWithDb->getWinner());

        self::assertSame($electionInMemory->countVotes(),$electionWithDb->countVotes());
        self::assertSame($electionInMemory->getVotesListAsString(),$electionWithDb->getVotesListAsString());
        self::assertSame($this->hashVotesList($electionInMemory),$this->hashVotesList($electionWithDb));
        self::assertSame(0,$electionWithDb->getVotesManager()->getContainerSize());
        self::assertLessThanOrEqual(ArrayManager::$CacheSize,$electionWithDb->getVotesManager()->getCacheSize());

        // Delete 3 votes
        unset($electionInMemory->getVotesManager()[13]);
        unset($electionInMemory->getVotesManager()[100]);
        unset($electionInMemory->getVotesManager()[102]);
        unset($electionWithDb->getVotesManager()[13]);
        unset($electionWithDb->getVotesManager()[100]);
        unset($electionWithDb->getVotesManager()[102]);

        self::assertSame(   $electionWithDb->countVotes(),
                            $handlerDriver->countEntities() + $electionWithDb->getVotesManager()->getContainerSize() );
        self::assertSame($electionInMemory->countVotes(),$electionWithDb->countVotes());
        self::assertSame($electionInMemory->getVotesListAsString(),$electionWithDb->getVotesListAsString());
        self::assertSame($this->hashVotesList($electionInMemory),$this->hashVotesList($electionWithDb));
        self::assertSame(0,$electionWithDb->getVotesManager()->getContainerSize());
        self::assertLessThanOrEqual(ArrayManager::$CacheSize,$electionWithDb->getVotesManager()->getCacheSize());
        self::assertArrayNotHasKey(13,$electionWithDb->getVotesManager()->debugGetCache());
        self::assertArrayNotHasKey(102,$electionWithDb->getVotesManager()->debugGetCache());
        self::assertArrayNotHasKey(100,$electionWithDb->getVotesManager()->debugGetCache());
        self::assertArrayHasKey(101,$electionWithDb->getVotesManager()->debugGetCache());


        // Unset Handler
        $electionWithDb->removeExternalDataHandler();

        self::assertEmpty($electionWithDb->getVotesManager()->debugGetCache());
        self::assertSame($electionInMemory->getVotesManager()->getContainerSize(),$electionWithDb->getVotesManager()->getContainerSize());
        self::assertSame($electionInMemory->countVotes(),$electionWithDb->countVotes());
        self::assertSame($electionInMemory->getVotesListAsString(),$electionWithDb->getVotesListAsString());
        self::assertSame($this->hashVotesList($electionInMemory),$this->hashVotesList($electionWithDb));

        // Change my mind : Set again the a new handler
        unset($handlerDriver);
        $electionWithDb->setExternalDataHandler($handlerDriver = new PdoHandlerDriver ($this->getPDO(),true));

        self::assertEmpty($electionWithDb->getVotesManager()->debugGetCache());
        self::assertSame(0,$electionWithDb->getVotesManager()->getContainerSize());
        self::assertSame($electionInMemory->countVotes(),$electionWithDb->countVotes());
        self::assertSame($electionInMemory->getVotesListAsString(),$electionWithDb->getVotesListAsString());
        self::assertSame($this->hashVotesList($electionInMemory),$this->hashVotesList($electionWithDb));

        self::assertTrue($electionWithDb->removeExternalDataHandler());

        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(23);

        $electionWithDb->removeExternalDataHandler();
    }

    public function testVotePreserveTag() : void
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver ($this->getPDO(),true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes(    'A > B > C * 5
                                        tag1 || B > A > C * 3');

        self::assertSame(5,$electionWithDb->countVotes('tag1',false));
        self::assertSame(3,$electionWithDb->countVotes('tag1',true));

        $electionWithDb->parseVotes(    'A > B > C * 5
                                        tag1 || B > A > C * 3');

        self::assertSame(10,$electionWithDb->countVotes('tag1',false));
        self::assertSame(6,$electionWithDb->countVotes('tag1',true));
    }

    public function testVoteObjectIntoDataHandler() : void
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver ($this->getPDO(),true));

        $electionWithDb->parseCandidates('A;B;C');

        $myVote = $electionWithDb->addVote('A>B>C');

        $electionWithDb->getVotesManager()->regularize();
        self::assertSame(0,$electionWithDb->getVotesManager()->getContainerSize());

        // myVote is no longer a part of the election. Internally, it will work with clones.
        self::assertSame(0,$myVote->countLinks());
        self::assertNotSame($electionWithDb->getVotesList()[0],$myVote);
        self::assertTrue($electionWithDb->getVotesList()[0]->haveLink($electionWithDb));
    }

    public function testUpdateEntity() : void
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver ($this->getPDO(),true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes('A>B>C * 19');
        $electionWithDb->addVote('C>B>A','voteToUpdate');

        $vote = $electionWithDb->getVotesList('voteToUpdate',true)[19];
        $vote->setRanking('B>A>C');
        $vote = null;

        $electionWithDb->parseVotes('A>B>C * 20');

        self::assertSame(
            "A > B > C * 39\n".
            "B > A > C * 1",
        $electionWithDb->getVotesListAsString());
    }

    public function testGetVotesListGenerator() : void
    {
        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver ($this->getPDO(),true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes('A>B>C * 10;tag42 || C>B>A * 42');

        $votesListGenerator = [];

        foreach ($electionWithDb->getVotesListGenerator() as $key => $value) :
            $votesListGenerator[$key] = $value;
        endforeach;

        self::assertCount(52,$votesListGenerator);


        $votesListGenerator = [];

        foreach ($electionWithDb->getVotesListGenerator('tag42') as $key => $value) :
            $votesListGenerator[$key] = $value;
        endforeach;

        self::assertCount(42,$votesListGenerator);
    }

    public function testSliceInput() : void
    {
        // Setup
        ArrayManager::$CacheSize = 462;
        ArrayManager::$MaxContainerLength = 462;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver ($this->getPDO(),true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes('A>B>C * 463');

        self::assertSame(463,$electionWithDb->countVotes());
    }

    public function testMultipleHandler () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(24);

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver ($this->getPDO(),true));
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver ($this->getPDO(),true));
    }

    public function testBadTableSchema1 () : void
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(0);

        $pdo = $this->getPDO();
        $handlerDriver = new PdoHandlerDriver ($pdo, true, ['tableName' => 'Entity', 'primaryColumnName' => 42]);
    }

    public function testBadTableSchema2 () : void
    {
        $this->expectException(\Exception::class);

        $pdo = $this->getPDO();
        $handlerDriver = new PdoHandlerDriver ($pdo, true, ['tableName' => 'B@adName', 'primaryColumnName' => 'id', 'dataColumnName' => 'data']);
    }

    public function testEmptyEntities () : void
    {
        $pdo = $this->getPDO();
        $handlerDriver = new PdoHandlerDriver ($pdo, true, ['tableName' => 'Entity', 'primaryColumnName' => 'id', 'dataColumnName' => 'data']);

        self::assertFalse( $handlerDriver->selectOneEntity(500));

        self::assertSame([], $handlerDriver->selectRangeEntities(500,5));
    }
}
