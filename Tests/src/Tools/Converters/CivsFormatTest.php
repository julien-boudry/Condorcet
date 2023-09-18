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

        expect($r)->toBe(<<<'CIVS'
            # Candidates: A / B / C
            1,2,3
            1,2,3
            3,2,1
            3,2,1
            2,1,1
            2,1,1
            CIVS);
    }

    public function testImplicit(): void
    {
        $this->election->parseVotes('A>B');
        $this->election->parseVotes('C');
        $this->election->parseVotes('A=B');

        $r = CivsFormat::createFromElection($this->election);

        expect($r)->toBe(<<<'CIVS'
            # Candidates: A / B / C
            1,2,3
            2,2,1
            1,1,2
            CIVS);
    }

    public function testExplicit(): void
    {
        $this->election->setImplicitRanking(false);

        $this->election->parseVotes('A>B');
        $this->election->parseVotes('C');
        $this->election->parseVotes('A=B');

        $r = CivsFormat::createFromElection($this->election);

        expect($r)->toBe(<<<'CIVS'
            # Candidates: A / B / C
            1,2,-
            -,-,1
            1,1,-
            CIVS);
    }

    public function testWeight(): void
    {
        $this->election->parseVotes('A>B>C ^3');

        // Deactivated
        $r = CivsFormat::createFromElection($this->election);

        expect($r)->toBe(<<<'CIVS'
            # Candidates: A / B / C
            1,2,3
            CIVS);

        //A ctivated
        $this->election->allowsVoteWeight(true);

        $r = CivsFormat::createFromElection($this->election);

        expect($r)->toBe(<<<'CIVS'
            # Candidates: A / B / C
            1,2,3
            1,2,3
            1,2,3
            CIVS);
    }

    public function testWriteToFile(): void
    {
        $file = new SplTempFileObject;

        expect($file->key())->toBe(0);

        $this->election->parseVotes('A>B; B>C');

        CivsFormat::createFromElection(election: $this->election, file: $file);

        $file->seek(0);
        expect($file->current())->toBe("# Candidates: A / B / C\n");

        $file->seek(1);
        expect($file->current())->toBe("1,2,3\n");

        $file->seek(2);
        expect($file->current())->toBe('3,1,2');

        expect($file->eof())->toBeTrue();
    }
}
