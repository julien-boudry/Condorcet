<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CivsFormat;
use PHPUnit\Framework\TestCase;
use SplTempFileObject;

class CivsFormatTest extends TestCase
{
    protected Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
        $this->election->parseCandidates('A;B;C');
    }

    public function testSimple(): void
    {
        $this->election->parseVotes('A>B>C * 2');
        $this->election->parseVotes('C>B>A * 2');
        $this->election->parseVotes('B=C>A * 2');

        $r = CivsFormat::createFromElection($this->election);

        self::assertSame(
            <<<'CIVS'
                # Candidates: A / B / C
                1,2,3
                1,2,3
                3,2,1
                3,2,1
                2,1,1
                2,1,1
                CIVS
            ,
            $r
        );
    }

    public function testImplicit(): void
    {
        $this->election->parseVotes('A>B');
        $this->election->parseVotes('C');
        $this->election->parseVotes('A=B');

        $r = CivsFormat::createFromElection($this->election);

        self::assertSame(
            <<<'CIVS'
                # Candidates: A / B / C
                1,2,3
                2,2,1
                1,1,2
                CIVS
            ,
            $r
        );
    }

    public function testExplicit(): void
    {
        $this->election->setImplicitRanking(false);

        $this->election->parseVotes('A>B');
        $this->election->parseVotes('C');
        $this->election->parseVotes('A=B');

        $r = CivsFormat::createFromElection($this->election);

        self::assertSame(
            <<<'CIVS'
                # Candidates: A / B / C
                1,2,-
                -,-,1
                1,1,-
                CIVS
            ,
            $r
        );
    }

    public function testWeight(): void
    {
        $this->election->parseVotes('A>B>C ^3');

        // Deactivated
        $r = CivsFormat::createFromElection($this->election);

        self::assertSame(
            <<<'CIVS'
                # Candidates: A / B / C
                1,2,3
                CIVS
            ,
            $r
        );

        //A ctivated
        $this->election->allowsVoteWeight(true);

        $r = CivsFormat::createFromElection($this->election);

        self::assertSame(
            <<<'CIVS'
                # Candidates: A / B / C
                1,2,3
                1,2,3
                1,2,3
                CIVS
            ,
            $r
        );
    }

    public function testWriteToFile(): void
    {
        $file = new SplTempFileObject;

        self::assertSame(0, $file->key());

        $this->election->parseVotes('A>B; B>C');

        CivsFormat::createFromElection(election: $this->election, file: $file);

        $file->seek(0);
        self::assertSame("# Candidates: A / B / C\n", $file->current());

        $file->seek(1);
        self::assertSame("1,2,3\n", $file->current());

        $file->seek(2);
        self::assertSame('3,1,2', $file->current());

        self::assertTrue($file->eof());
    }
}
