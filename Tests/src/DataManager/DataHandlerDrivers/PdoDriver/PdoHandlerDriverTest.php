<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\DataManager\DataHandlerDrivers\PdoDriver;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\DataManager\ArrayManager;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver;
use CondorcetPHP\Condorcet\Throwable\DataHandlerException;
use PHPUnit\Framework\Attributes\{Group, RequiresPhpExtension};
use PHPUnit\Framework\TestCase;

#[Group('DataHandlerDrivers')]
#[RequiresPhpExtension('pdo_sqlite')]
class PdoHandlerDriverTest extends TestCase
{
    protected function getPDO(): \PDO
    {
        return new \PDO('sqlite::memory:', '', '', [\PDO::ATTR_PERSISTENT => false]);
    }

    protected function hashVotesList(Election $elec): string
    {
        $c = 0;
        $voteCompil = '';
        foreach ($elec->getVotesManager() as $oneVote) {
            $c++;
            $voteCompil .= (string) $oneVote;
        }

        return $c.'||'.hash('md5', $voteCompil);
    }


    public function testManyVoteManipulation(): never
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionInMemory = new Election;
        $electionWithDb->setExternalDataHandler($handlerDriver = new PdoHandlerDriver($this->getPDO(), true));

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

        $this->assertSame(
            $electionWithDb->countVotes(),
            $handlerDriver->countEntities() + $electionWithDb->getVotesManager()->getContainerSize()
        );

        $this->assertSame($electionInMemory->countVotes(), $electionWithDb->countVotes());
        $this->assertSame($electionInMemory->getVotesListAsString(), $electionWithDb->getVotesListAsString());
        $this->assertSame($this->hashVotesList($electionInMemory), $this->hashVotesList($electionWithDb));

        $this->assertEquals($electionInMemory->getPairwise()->getExplicitPairwise(), $electionWithDb->getPairwise()->getExplicitPairwise());
        $this->assertEquals((string) $electionInMemory->getWinner('Ranked Pairs Winning'), (string) $electionWithDb->getWinner('Ranked Pairs Winning'));
        $this->assertEquals((string) $electionInMemory->getWinner(), (string) $electionWithDb->getWinner());
        $this->assertEquals((string) $electionInMemory->getCondorcetWinner(), (string) $electionWithDb->getCondorcetWinner());


        // 58 Votes
        $votes = 'A > B > C > E * 58';

        $electionWithDb->parseVotes($votes);
        $electionInMemory->parseVotes($votes);

        $this->assertSame(58 % ArrayManager::$MaxContainerLength, $electionWithDb->getVotesManager()->getContainerSize());
        $this->assertSame(
            $electionWithDb->countVotes(),
            $handlerDriver->countEntities() + $electionWithDb->getVotesManager()->getContainerSize()
        );

        $this->assertEquals('A', $electionWithDb->getWinner());
        $this->assertEquals((string) $electionInMemory->getWinner(), (string) $electionWithDb->getWinner());

        $this->assertSame($electionInMemory->countVotes(), $electionWithDb->countVotes());
        $this->assertSame($electionInMemory->getVotesListAsString(), $electionWithDb->getVotesListAsString());
        $this->assertSame($this->hashVotesList($electionInMemory), $this->hashVotesList($electionWithDb));
        $this->assertSame(0, $electionWithDb->getVotesManager()->getContainerSize());
        $this->assertLessThanOrEqual(ArrayManager::$CacheSize, $electionWithDb->getVotesManager()->getCacheSize());

        // Delete 3 votes
        unset($electionInMemory->getVotesManager()[13]);
        unset($electionInMemory->getVotesManager()[100]);
        unset($electionInMemory->getVotesManager()[102]);
        unset($electionWithDb->getVotesManager()[13]);
        unset($electionWithDb->getVotesManager()[100]);
        unset($electionWithDb->getVotesManager()[102]);

        $this->assertSame(
            $electionWithDb->countVotes(),
            $handlerDriver->countEntities() + $electionWithDb->getVotesManager()->getContainerSize()
        );
        $this->assertSame($electionInMemory->countVotes(), $electionWithDb->countVotes());
        $this->assertSame($electionInMemory->getVotesListAsString(), $electionWithDb->getVotesListAsString());
        $this->assertSame($this->hashVotesList($electionInMemory), $this->hashVotesList($electionWithDb));
        $this->assertSame(0, $electionWithDb->getVotesManager()->getContainerSize());
        $this->assertLessThanOrEqual(ArrayManager::$CacheSize, $electionWithDb->getVotesManager()->getCacheSize());
        $this->assertArrayNotHasKey(13, $electionWithDb->getVotesManager()->debugGetCache());
        $this->assertArrayNotHasKey(102, $electionWithDb->getVotesManager()->debugGetCache());
        $this->assertArrayNotHasKey(100, $electionWithDb->getVotesManager()->debugGetCache());
        $this->assertArrayHasKey(101, $electionWithDb->getVotesManager()->debugGetCache());


        // Unset Handler
        $electionWithDb->removeExternalDataHandler();

        $this->assertEmpty($electionWithDb->getVotesManager()->debugGetCache());
        $this->assertSame($electionInMemory->getVotesManager()->getContainerSize(), $electionWithDb->getVotesManager()->getContainerSize());
        $this->assertSame($electionInMemory->countVotes(), $electionWithDb->countVotes());
        $this->assertSame($electionInMemory->getVotesListAsString(), $electionWithDb->getVotesListAsString());
        $this->assertSame($this->hashVotesList($electionInMemory), $this->hashVotesList($electionWithDb));

        // Change my mind : Set again the a new handler
        unset($handlerDriver);
        $electionWithDb->setExternalDataHandler($handlerDriver = new PdoHandlerDriver($this->getPDO(), true));

        $this->assertEmpty($electionWithDb->getVotesManager()->debugGetCache());
        $this->assertSame(0, $electionWithDb->getVotesManager()->getContainerSize());
        $this->assertSame($electionInMemory->countVotes(), $electionWithDb->countVotes());
        $this->assertSame($electionInMemory->getVotesListAsString(), $electionWithDb->getVotesListAsString());
        $this->assertSame($this->hashVotesList($electionInMemory), $this->hashVotesList($electionWithDb));

        $this->assertTrue($electionWithDb->removeExternalDataHandler());

        $this->expectException(DataHandlerException::class);
        $this->expectExceptionMessage('Problem with data handler: external data handler cannot be removed, is already in use');

        $electionWithDb->removeExternalDataHandler();
    }

    public function testVotePreserveTag(): void
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver($this->getPDO(), true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes('A > B > C * 5
                                        tag1 || B > A > C * 3');

        $this->assertSame(5, $electionWithDb->countVotes('tag1', false));
        $this->assertSame(3, $electionWithDb->countVotes('tag1', true));

        $electionWithDb->parseVotes('A > B > C * 5
                                        tag1 || B > A > C * 3');

        $this->assertSame(10, $electionWithDb->countVotes('tag1', false));
        $this->assertSame(6, $electionWithDb->countVotes('tag1', true));
    }

    public function testVoteObjectIntoDataHandler(): void
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver($this->getPDO(), true));

        $electionWithDb->parseCandidates('A;B;C');

        $myVote = $electionWithDb->addVote('A>B>C');

        $electionWithDb->getVotesManager()->regularize();
        $this->assertSame(0, $electionWithDb->getVotesManager()->getContainerSize());

        // myVote is no longer a part of the election. Internally, it will work with clones.
        $this->assertSame(0, $myVote->countLinks());
        $this->assertNotSame($electionWithDb->getVotesList()[0], $myVote);
        $this->assertTrue($electionWithDb->getVotesList()[0]->haveLink($electionWithDb));
    }

    public function testUpdateEntity(): void
    {
        // Setup
        ArrayManager::$CacheSize = 10;
        ArrayManager::$MaxContainerLength = 10;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver($this->getPDO(), true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes('A>B>C * 19');
        $electionWithDb->addVote('C>B>A', 'voteToUpdate');

        $vote = $electionWithDb->getVotesList('voteToUpdate', true)[19];
        $vote->setRanking('B>A>C');
        $vote = null;

        $electionWithDb->parseVotes('A>B>C * 20');

        $this->assertSame(
            "A > B > C * 39\n".
            'B > A > C * 1',
            $electionWithDb->getVotesListAsString()
        );
    }

    public function testGetVotesListGenerator(): void
    {
        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver($this->getPDO(), true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes('A>B>C * 10;tag42 || C>B>A * 42');

        $votesListGenerator = [];

        foreach ($electionWithDb->getVotesListGenerator() as $key => $value) {
            $votesListGenerator[$key] = $value;
        }

        $this->assertCount(52, $votesListGenerator);


        $votesListGenerator = [];

        foreach ($electionWithDb->getVotesListGenerator('tag42') as $key => $value) {
            $votesListGenerator[$key] = $value;
        }

        $this->assertCount(42, $votesListGenerator);
    }

    public function testSliceInput(): void
    {
        // Setup
        ArrayManager::$CacheSize = 462;
        ArrayManager::$MaxContainerLength = 462;

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver($this->getPDO(), true));

        $electionWithDb->parseCandidates('A;B;C');

        $electionWithDb->parseVotes('A>B>C * 463');

        $this->assertSame(463, $electionWithDb->countVotes());
    }

    public function testMultipleHandler(): never
    {
        $this->expectException(DataHandlerException::class);
        $this->expectExceptionMessage('external data handler cannot be imported');

        $electionWithDb = new Election;
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver($this->getPDO(), true));
        $electionWithDb->setExternalDataHandler(new PdoHandlerDriver($this->getPDO(), true));
    }

    public function testBadTableSchema1(): never
    {
        $this->expectException(DataHandlerException::class);
        $this->expectExceptionMessage('Problem with data handler: invalid structure template for PdoHandler');

        $pdo = $this->getPDO();
        new PdoHandlerDriver($pdo, true, ['tableName' => 'Entity', 'primaryColumnName' => 42]);
    }

    public function testBadTableSchema2(): never
    {
        $this->expectException(\Exception::class);

        $pdo = $this->getPDO();
        new PdoHandlerDriver($pdo, true, ['tableName' => 'B@adName', 'primaryColumnName' => 'id', 'dataColumnName' => 'data']);
    }

    public function testEmptyEntities(): void
    {
        $pdo = $this->getPDO();
        $handlerDriver = new PdoHandlerDriver($pdo, true, ['tableName' => 'Entity', 'primaryColumnName' => 'id', 'dataColumnName' => 'data']);

        $this->assertFalse($handlerDriver->selectOneEntity(500));

        $this->assertSame([], $handlerDriver->selectRangeEntities(500, 5));
    }
}
