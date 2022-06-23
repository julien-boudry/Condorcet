<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\DebianFormat;
use PHPUnit\Framework\TestCase;

class DebianFormatTest extends TestCase
{
    private static DebianFormat $debian2020;
    private static DebianFormat $debian2007;
    private static DebianFormat $debian2006;

    protected function setUp(): void
    {
        self::$debian2020 ?? (self::$debian2020 = new DebianFormat(__DIR__.'/DebianData/leader2020_tally.txt'));
        self::$debian2007 ?? (self::$debian2007 = new DebianFormat(__DIR__.'/DebianData/leader2007_tally.txt'));
        self::$debian2006 ?? (self::$debian2006 = new DebianFormat(__DIR__.'/DebianData/leader2006_tally.txt'));
    }

    public function test2020_Implicit(): void
    {
        $election = self::$debian2020->setDataToAnElection();

        self::assertSame(339, $election->countVotes());
        self::assertSame(1, $election->getNumberOfSeats());

        self::assertSame(
            'Jonathan Carter > Sruthi Chandran > Brian Gupta > None Of The Above',
            $election->getResult('Schulze Margin')->getResultAsString()
        );

        self::assertSame(
            unserialize('a:4:{s:15:"Jonathan Carter";a:3:{s:15:"Sruthi Chandran";i:201;s:11:"Brian Gupta";i:241;s:17:"None Of The Above";i:267;}s:15:"Sruthi Chandran";a:3:{s:15:"Jonathan Carter";i:0;s:11:"Brian Gupta";i:49;s:17:"None Of The Above";i:168;}s:11:"Brian Gupta";a:3:{s:15:"Jonathan Carter";i:0;s:15:"Sruthi Chandran";i:0;s:17:"None Of The Above";i:69;}s:17:"None Of The Above";a:3:{s:15:"Jonathan Carter";i:0;s:15:"Sruthi Chandran";i:0;s:11:"Brian Gupta";i:0;}}'),
            $election->getResult('Schulze Margin')->getStats()
        );
    }

    public function test2020_Explicit(): void
    {
        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(1);

        self::$debian2020->setDataToAnElection($election);

        self::assertSame(339, $election->countVotes());

        self::assertSame(
            'Jonathan Carter > Sruthi Chandran > Brian Gupta > None Of The Above',
            $election->getResult('Schulze Margin')->getResultAsString()
        );
    }

    public function test2007_Implicit(): void
    {
        $election = self::$debian2007->setDataToAnElection();

        self::assertSame(482, $election->countVotes());
        self::assertSame(1, $election->getNumberOfSeats());

        self::assertSame(
            'Sam Hocevar > Steve McIntyre > Raphaël Hertzog > Wouter Verhelst > Anthony Towns > Gustavo Franco > None Of The Above > Simon Richter > Aigars Mahinovs',
            $election->getResult('Schulze Margin')->getResultAsString()
        );
    }

    public function test2007_Explicit(): void
    {
        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(1);

        self::$debian2007->setDataToAnElection($election);

        self::assertSame(482, $election->countVotes());

        self::assertSame(
            'Sam Hocevar > Steve McIntyre > Raphaël Hertzog > Wouter Verhelst > Anthony Towns > Gustavo Franco > None Of The Above > Simon Richter > Aigars Mahinovs',
            $election->getResult('Schulze Margin')->getResultAsString()
        );
    }

    public function test2006_Implicit(): void
    {
        $election = self::$debian2006->setDataToAnElection();

        self::assertSame(421, $election->countVotes());
        self::assertSame(1, $election->getNumberOfSeats());

        self::assertSame(
            'Anthony Towns > Steve McIntyre > Andreas Schuldei = Jeroen van Wolffelaar > Bill Allombert > None of the Above > Ari Pollak > Jonathan aka Ted Walther',
            $election->getResult('Schulze Margin')->getResultAsString()
        );
    }

    public function test2006_Explicit(): void
    {
        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(1);

        self::$debian2006->setDataToAnElection($election);

        self::assertSame(421, $election->countVotes());

        self::assertSame(
            'Steve McIntyre > Anthony Towns > Jeroen van Wolffelaar > Andreas Schuldei > Bill Allombert > None of the Above > Ari Pollak > Jonathan aka Ted Walther',
            $election->getResult('Schulze Margin')->getResultAsString()
        );
    }
}
