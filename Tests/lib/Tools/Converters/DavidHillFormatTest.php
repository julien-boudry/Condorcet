<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat;
use CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat;
use PHPUnit\Framework\TestCase;

class DavidHillFormatTest extends TestCase
{
    private static DavidHillFormat $tidemanA77;

    public function setUp(): void
    {
        self::$tidemanA77 ?? (self::$tidemanA77 = new DavidHillFormat(__DIR__.'/TidemanData/A77.HIL'));
    }

    public function testA77_With_Implicit (): void
    {
        $election = self::$tidemanA77->setDataToAnElection();

        self::assertSame(213,$election->countVotes());
        self::assertSame(1,$election->getNumberOfSeats());

        self::assertSame(<<<EOD
            3 > 1 = 2 * 39
            1 > 3 > 2 * 38
            3 > 1 > 2 * 36
            3 > 2 > 1 * 29
            1 > 2 > 3 * 28
            2 > 1 > 3 * 15
            1 > 2 = 3 * 14
            2 > 3 > 1 * 9
            2 > 1 = 3 * 5
            EOD,
            $election->getVotesListAsString()
        );
    }

    public function testA77_With_Explicit (): void
    {
        $election = new Election;
        $election->setImplicitRanking(false);

        self::$tidemanA77->setDataToAnElection($election);

        self::assertSame(213,$election->countVotes());

        self::assertSame(<<<EOD
            3 * 39
            1 > 3 * 38
            3 > 1 * 36
            3 > 2 * 29
            1 > 2 * 28
            2 > 1 * 15
            1 * 14
            2 > 3 * 9
            2 * 5
            EOD,
            $election->getVotesListAsString()
        );
    }

    public function testA1_ForCandidatesNames (): void
    {
        $election = (new DavidHillFormat(__DIR__.'/TidemanData/A1.HIL'))->setDataToAnElection();

        self::assertSame(380,$election->countVotes());
        self::assertSame(3,$election->getNumberOfSeats());

        self::assertSame(<<<EOD
            Candidate  3 > Candidate  1 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 13
            Candidate  1 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 9
            Candidate  1 > Candidate  3 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 9
            Candidate  2 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 6
            Candidate  1 > Candidate  5 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 5
            Candidate  1 > Candidate  3 > Candidate  9 > Candidate  7 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 4
            Candidate  1 > Candidate  8 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 4
            Candidate  1 > Candidate  9 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 4
            Candidate  3 > Candidate  6 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  9 = Candidate 10 * 4
            Candidate  4 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 4
            Candidate  7 > Candidate  9 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 4
            Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 4
            Candidate  9 > Candidate  8 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 4
            Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 3
            Candidate  1 > Candidate  3 > Candidate  2 > Candidate  7 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 3
            Candidate  1 > Candidate  3 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 3
            Candidate  1 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 3
            Candidate  1 > Candidate  4 > Candidate  9 > Candidate  3 > Candidate  8 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 3
            Candidate  1 > Candidate  5 > Candidate  9 > Candidate  2 > Candidate  7 > Candidate 10 > Candidate  3 = Candidate  4 = Candidate  6 = Candidate  8 * 3
            Candidate  1 > Candidate  7 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 3
            Candidate  1 > Candidate  8 > Candidate  4 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 3
            Candidate  1 > Candidate  9 > Candidate  5 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 3
            Candidate  2 > Candidate  4 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 3
            Candidate  2 > Candidate  9 > Candidate  7 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 3
            Candidate  3 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 3
            Candidate  7 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 3
            Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 3
            Candidate  1 > Candidate  3 > Candidate  2 > Candidate  7 > Candidate  9 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 2
            Candidate  1 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 2
            Candidate  1 > Candidate  7 > Candidate  9 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 2
            Candidate  1 > Candidate  9 > Candidate  8 > Candidate  2 > Candidate  3 > Candidate  7 > Candidate  4 > Candidate 10 > Candidate  5 = Candidate  6 * 2
            Candidate  1 > Candidate 10 > Candidate  9 > Candidate  2 > Candidate  3 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  7 > Candidate  8 * 2
            Candidate  2 > Candidate  3 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 2
            Candidate  2 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
            Candidate  2 > Candidate  7 > Candidate  9 > Candidate  8 > Candidate 10 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 * 2
            Candidate  2 > Candidate  8 > Candidate  7 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 2
            Candidate  2 > Candidate  8 > Candidate  7 > Candidate  9 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 2
            Candidate  2 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 2
            Candidate  4 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
            Candidate  4 > Candidate  3 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 2
            Candidate  4 > Candidate  9 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate 10 * 2
            Candidate  6 > Candidate  7 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  9 = Candidate 10 * 2
            Candidate  6 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate  1 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate 10 * 2
            Candidate  7 > Candidate  1 > Candidate  3 > Candidate  9 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
            Candidate  8 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  2 > Candidate  5 > Candidate 10 > Candidate  9 > Candidate  7 > Candidate  6 * 2
            Candidate  8 > Candidate  1 > Candidate  7 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
            Candidate  8 > Candidate  1 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 2
            Candidate  8 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 2
            Candidate  8 > Candidate  2 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 2
            Candidate  8 > Candidate  2 > Candidate  6 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  9 = Candidate 10 * 2
            Candidate  8 > Candidate  2 > Candidate  7 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
            Candidate  8 > Candidate  3 > Candidate  1 > Candidate  6 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  9 = Candidate 10 * 2
            Candidate  8 > Candidate  3 > Candidate  1 > Candidate  6 > Candidate  9 > Candidate  7 > Candidate 10 > Candidate  2 = Candidate  4 = Candidate  5 * 2
            Candidate  9 > Candidate  1 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
            Candidate  9 > Candidate  2 > Candidate  7 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
            Candidate  9 > Candidate  4 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
            Candidate  9 > Candidate  4 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 2
            Candidate  9 > Candidate  4 > Candidate  3 > Candidate  1 > Candidate  7 > Candidate  8 > Candidate  6 > Candidate  2 > Candidate 10 > Candidate  5 * 2
            Candidate  9 > Candidate  7 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 2
            Candidate  9 > Candidate  8 > Candidate  3 > Candidate  6 > Candidate  7 > Candidate  2 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate 10 * 2
            Candidate  9 > Candidate  8 > Candidate  4 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 2
            Candidate  9 > Candidate 10 > Candidate  2 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 2
            Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 2
            Candidate 10 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 2
            Candidate 10 > Candidate  6 > Candidate  1 > Candidate  8 > Candidate  3 > Candidate  5 > Candidate  2 = Candidate  4 = Candidate  7 = Candidate  9 * 2
            Candidate 10 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 2
            Candidate  1 > Candidate  2 > Candidate  3 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate  5 > Candidate  6 > Candidate 10 > Candidate  4 * 1
            Candidate  1 > Candidate  2 > Candidate  4 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  2 > Candidate  4 > Candidate  3 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  2 > Candidate  4 > Candidate  5 > Candidate  8 > Candidate 10 > Candidate  3 = Candidate  6 = Candidate  7 = Candidate  9 * 1
            Candidate  1 > Candidate  2 > Candidate  5 > Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  4 > Candidate  8 > Candidate  6 > Candidate  5 > Candidate  7 > Candidate  9 > Candidate 10 > Candidate  2 * 1
            Candidate  1 > Candidate  3 > Candidate  4 > Candidate  9 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  4 > Candidate  9 > Candidate  5 > Candidate  6 > Candidate 10 > Candidate  2 > Candidate  8 > Candidate  7 * 1
            Candidate  1 > Candidate  3 > Candidate  5 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  5 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  6 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  7 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  7 > Candidate  8 > Candidate 10 > Candidate  9 > Candidate  2 > Candidate  6 > Candidate  4 > Candidate  5 * 1
            Candidate  1 > Candidate  3 > Candidate  7 > Candidate  9 > Candidate  2 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  8 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  1 > Candidate  3 > Candidate  9 > Candidate  8 > Candidate  4 > Candidate  2 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  1 > Candidate  3 > Candidate  9 > Candidate 10 > Candidate  7 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 * 1
            Candidate  1 > Candidate  4 > Candidate  3 > Candidate  6 > Candidate  8 > Candidate  5 > Candidate  2 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  4 > Candidate  3 > Candidate  6 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  1 > Candidate  4 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  4 > Candidate  5 > Candidate  8 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  9 * 1
            Candidate  1 > Candidate  4 > Candidate  5 > Candidate  9 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  1 > Candidate  4 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  4 > Candidate  8 > Candidate  7 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  4 > Candidate  9 > Candidate 10 > Candidate  6 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  7 = Candidate  8 * 1
            Candidate  1 > Candidate  4 > Candidate 10 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  1 > Candidate  5 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  5 > Candidate  4 > Candidate  9 > Candidate  2 > Candidate  3 > Candidate  7 > Candidate  6 > Candidate  8 > Candidate 10 * 1
            Candidate  1 > Candidate  6 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  6 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  7 > Candidate  9 > Candidate  6 > Candidate  2 > Candidate  3 > Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
            Candidate  1 > Candidate  7 > Candidate  9 > Candidate  8 > Candidate  3 > Candidate  4 > Candidate  6 > Candidate 10 > Candidate  5 > Candidate  2 * 1
            Candidate  1 > Candidate  7 > Candidate  9 > Candidate  8 > Candidate  6 > Candidate  3 > Candidate  2 > Candidate 10 > Candidate  4 > Candidate  5 * 1
            Candidate  1 > Candidate  8 > Candidate  3 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  1 > Candidate  8 > Candidate  3 > Candidate  9 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  1 > Candidate  8 > Candidate  6 > Candidate  4 > Candidate  2 > Candidate  3 > Candidate  5 > Candidate 10 > Candidate  9 > Candidate  7 * 1
            Candidate  1 > Candidate  8 > Candidate  9 > Candidate  3 > Candidate  4 > Candidate  2 > Candidate  6 > Candidate  5 > Candidate  7 > Candidate 10 * 1
            Candidate  1 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  1 > Candidate  9 > Candidate  2 > Candidate  4 > Candidate  8 > Candidate 10 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  1 > Candidate  9 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  1 > Candidate  9 > Candidate  4 > Candidate  7 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  1 > Candidate  9 > Candidate  7 > Candidate  4 > Candidate  5 > Candidate  2 > Candidate 10 > Candidate  8 > Candidate  6 > Candidate  3 * 1
            Candidate  1 > Candidate  9 > Candidate  7 > Candidate  6 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
            Candidate  1 > Candidate  9 > Candidate  8 > Candidate  3 > Candidate 10 > Candidate  2 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  7 * 1
            Candidate  1 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate  1 > Candidate  9 > Candidate 10 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate  1 > Candidate  9 > Candidate 10 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate  1 > Candidate  9 > Candidate 10 > Candidate  4 > Candidate  7 > Candidate  3 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 * 1
            Candidate  1 > Candidate  9 > Candidate 10 > Candidate  4 > Candidate  8 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  1 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  4 > Candidate  3 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  1 > Candidate 10 > Candidate  3 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  1 > Candidate 10 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  1 > Candidate 10 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate  1 > Candidate 10 > Candidate  9 > Candidate  2 > Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate  1 > Candidate 10 > Candidate  9 > Candidate  4 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate  2 > Candidate  3 > Candidate  1 > Candidate  4 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  5 > Candidate  6 > Candidate  7 * 1
            Candidate  2 > Candidate  3 > Candidate  9 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  2 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  2 > Candidate  4 > Candidate  9 > Candidate  7 > Candidate  3 > Candidate  8 > Candidate 10 > Candidate  6 > Candidate  1 > Candidate  5 * 1
            Candidate  2 > Candidate  5 > Candidate  3 > Candidate  1 > Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  2 > Candidate  7 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  2 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  2 > Candidate  8 > Candidate  9 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  2 > Candidate  8 > Candidate 10 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 1
            Candidate  2 > Candidate  9 > Candidate  1 > Candidate 10 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  2 > Candidate  9 > Candidate  7 > Candidate  4 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  2 > Candidate  9 > Candidate  7 > Candidate  8 > Candidate  6 > Candidate 10 > Candidate  5 > Candidate  4 > Candidate  3 > Candidate  1 * 1
            Candidate  3 > Candidate  1 > Candidate  2 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  7 > Candidate  8 > Candidate  9 > Candidate 10 * 1
            Candidate  3 > Candidate  1 > Candidate  5 > Candidate  2 > Candidate  4 > Candidate  7 > Candidate  6 > Candidate  9 > Candidate  8 > Candidate 10 * 1
            Candidate  3 > Candidate  1 > Candidate  6 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  3 > Candidate  1 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  3 > Candidate  4 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  3 > Candidate  4 > Candidate  5 > Candidate  8 > Candidate 10 > Candidate  1 > Candidate  2 > Candidate  9 > Candidate  6 > Candidate  7 * 1
            Candidate  3 > Candidate  4 > Candidate  9 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  3 > Candidate  4 > Candidate 10 > Candidate  1 > Candidate  6 > Candidate  2 > Candidate  5 > Candidate  8 > Candidate  9 > Candidate  7 * 1
            Candidate  3 > Candidate  5 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  3 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  3 > Candidate  7 > Candidate  1 > Candidate  5 > Candidate  2 = Candidate  4 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  3 > Candidate  8 > Candidate  2 > Candidate  7 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
            Candidate  3 > Candidate  9 > Candidate  1 > Candidate  8 > Candidate  7 > Candidate  5 > Candidate  4 > Candidate  2 = Candidate  6 = Candidate 10 * 1
            Candidate  3 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  4 > Candidate  1 > Candidate  3 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  4 > Candidate  1 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  4 > Candidate  2 > Candidate  7 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  4 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  4 > Candidate  3 > Candidate  1 > Candidate  2 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  6 > Candidate  7 > Candidate  5 * 1
            Candidate  4 > Candidate  3 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  4 > Candidate  3 > Candidate  8 > Candidate  1 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 1
            Candidate  4 > Candidate  3 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  4 > Candidate  5 > Candidate  1 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 * 1
            Candidate  4 > Candidate  5 > Candidate  7 > Candidate 10 > Candidate  1 > Candidate  2 > Candidate  8 > Candidate  3 = Candidate  6 = Candidate  9 * 1
            Candidate  4 > Candidate  5 > Candidate  8 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  4 > Candidate  5 > Candidate  9 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  4 > Candidate  7 > Candidate 10 > Candidate  3 > Candidate  8 > Candidate  1 > Candidate  2 > Candidate  6 > Candidate  5 > Candidate  9 * 1
            Candidate  4 > Candidate  8 > Candidate  1 > Candidate  3 > Candidate  5 > Candidate  6 > Candidate 10 > Candidate  2 > Candidate  9 > Candidate  7 * 1
            Candidate  4 > Candidate  9 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  4 > Candidate  9 > Candidate  2 > Candidate  7 > Candidate  3 > Candidate  5 > Candidate 10 > Candidate  6 > Candidate  1 > Candidate  8 * 1
            Candidate  4 > Candidate  9 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  4 > Candidate 10 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  4 > Candidate 10 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  4 > Candidate 10 > Candidate  5 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  4 > Candidate 10 > Candidate  9 > Candidate  3 > Candidate  1 > Candidate  2 > Candidate  5 > Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate  5 > Candidate  8 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  5 > Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate  6 > Candidate  3 > Candidate  4 > Candidate  1 > Candidate  2 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  6 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  6 > Candidate  7 > Candidate  4 > Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  8 = Candidate  9 * 1
            Candidate  6 > Candidate  8 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate 10 * 1
            Candidate  6 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  6 > Candidate  9 > Candidate 10 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 * 1
            Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  7 > Candidate  1 > Candidate  8 > Candidate  9 > Candidate 10 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 * 1
            Candidate  7 > Candidate  1 > Candidate  9 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 * 1
            Candidate  7 > Candidate  2 > Candidate  6 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  7 > Candidate  2 > Candidate  9 > Candidate  6 > Candidate  8 > Candidate  3 > Candidate 10 > Candidate  5 > Candidate  1 > Candidate  4 * 1
            Candidate  7 > Candidate  2 > Candidate  9 > Candidate  8 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  7 > Candidate  2 > Candidate  9 > Candidate  8 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  7 > Candidate  3 > Candidate  4 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate  9 = Candidate 10 * 1
            Candidate  7 > Candidate  4 > Candidate  1 > Candidate  2 > Candidate  3 > Candidate  5 > Candidate  6 > Candidate  8 > Candidate  9 > Candidate 10 * 1
            Candidate  7 > Candidate  4 > Candidate  2 > Candidate  9 > Candidate  8 > Candidate  1 > Candidate  3 > Candidate  6 > Candidate 10 > Candidate  5 * 1
            Candidate  7 > Candidate  6 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
            Candidate  7 > Candidate  8 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
            Candidate  7 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  7 > Candidate  9 > Candidate  6 > Candidate  8 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate 10 * 1
            Candidate  7 > Candidate 10 > Candidate  1 > Candidate  4 > Candidate  3 > Candidate  8 > Candidate  9 > Candidate  2 = Candidate  5 = Candidate  6 * 1
            Candidate  8 > Candidate  1 > Candidate  5 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  8 > Candidate  1 > Candidate  9 > Candidate  4 > Candidate  3 > Candidate  2 > Candidate 10 > Candidate  5 > Candidate  6 > Candidate  7 * 1
            Candidate  8 > Candidate  2 > Candidate  7 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
            Candidate  8 > Candidate  4 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 = Candidate 10 * 1
            Candidate  8 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
            Candidate  8 > Candidate  7 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  9 = Candidate 10 * 1
            Candidate  8 > Candidate  7 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  8 > Candidate  7 > Candidate  9 > Candidate 10 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 * 1
            Candidate  8 > Candidate  9 > Candidate  1 > Candidate  2 > Candidate  7 > Candidate  4 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  8 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  8 > Candidate 10 > Candidate  3 > Candidate  1 > Candidate  2 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  9 * 1
            Candidate  8 > Candidate 10 > Candidate  7 > Candidate  6 > Candidate  9 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 * 1
            Candidate  8 > Candidate 10 > Candidate  9 > Candidate  7 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 * 1
            Candidate  9 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  7 > Candidate 10 > Candidate  8 > Candidate  6 > Candidate  2 > Candidate  5 * 1
            Candidate  9 > Candidate  1 > Candidate  4 > Candidate  3 > Candidate  7 > Candidate  8 > Candidate  2 > Candidate 10 > Candidate  6 > Candidate  5 * 1
            Candidate  9 > Candidate  1 > Candidate  7 > Candidate  3 > Candidate  8 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  9 > Candidate  1 > Candidate  8 > Candidate  3 > Candidate  4 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  9 > Candidate  2 > Candidate  7 > Candidate  3 > Candidate  4 > Candidate  5 > Candidate  6 > Candidate  8 > Candidate  1 > Candidate 10 * 1
            Candidate  9 > Candidate  2 > Candidate  7 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  2 > Candidate  7 > Candidate  8 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  9 > Candidate  2 > Candidate  7 > Candidate  8 > Candidate  6 > Candidate 10 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 * 1
            Candidate  9 > Candidate  2 > Candidate 10 > Candidate  6 > Candidate  5 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  7 = Candidate  8 * 1
            Candidate  9 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  3 > Candidate  4 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  4 > Candidate  1 > Candidate  2 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  4 > Candidate  8 > Candidate  1 > Candidate  2 > Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  9 > Candidate  4 > Candidate  8 > Candidate  1 > Candidate  3 > Candidate  7 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  9 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  6 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  6 > Candidate  7 > Candidate  2 > Candidate  3 > Candidate  4 > Candidate  5 > Candidate  1 > Candidate  8 > Candidate 10 * 1
            Candidate  9 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  7 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  7 > Candidate  2 > Candidate  1 > Candidate  5 > Candidate  3 = Candidate  4 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  7 > Candidate  2 > Candidate  6 > Candidate  8 > Candidate  4 > Candidate  1 = Candidate  3 = Candidate  5 = Candidate 10 * 1
            Candidate  9 > Candidate  7 > Candidate  2 > Candidate 10 > Candidate  3 > Candidate  1 > Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
            Candidate  9 > Candidate  7 > Candidate  3 > Candidate  1 > Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 = Candidate 10 * 1
            Candidate  9 > Candidate  7 > Candidate 10 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
            Candidate  9 > Candidate  8 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate 10 * 1
            Candidate  9 > Candidate  8 > Candidate  3 > Candidate  7 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  9 > Candidate  8 > Candidate  4 > Candidate  1 > Candidate  3 > Candidate 10 > Candidate  2 = Candidate  5 = Candidate  6 = Candidate  7 * 1
            Candidate  9 > Candidate  8 > Candidate  6 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  7 = Candidate 10 * 1
            Candidate  9 > Candidate  8 > Candidate  6 > Candidate  3 > Candidate  2 > Candidate  7 > Candidate 10 > Candidate  1 > Candidate  4 > Candidate  5 * 1
            Candidate  9 > Candidate  8 > Candidate  7 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  9 > Candidate  8 > Candidate  7 > Candidate  2 > Candidate  1 > Candidate 10 > Candidate  3 > Candidate  4 > Candidate  6 > Candidate  5 * 1
            Candidate  9 > Candidate  8 > Candidate  7 > Candidate  2 > Candidate  3 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate 10 * 1
            Candidate  9 > Candidate 10 > Candidate  2 > Candidate  6 > Candidate  7 > Candidate  8 > Candidate  4 > Candidate  5 > Candidate  1 > Candidate  3 * 1
            Candidate  9 > Candidate 10 > Candidate  7 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
            Candidate  9 > Candidate 10 > Candidate  7 > Candidate  4 > Candidate  1 > Candidate  2 > Candidate  6 > Candidate  3 > Candidate  5 > Candidate  8 * 1
            Candidate 10 > Candidate  1 > Candidate  3 > Candidate  4 > Candidate  2 > Candidate  5 > Candidate  6 > Candidate  9 > Candidate  8 > Candidate  7 * 1
            Candidate 10 > Candidate  1 > Candidate  9 > Candidate  2 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate 10 > Candidate  3 > Candidate  5 > Candidate  1 = Candidate  2 = Candidate  4 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate 10 > Candidate  4 > Candidate  1 = Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate 10 > Candidate  4 > Candidate  1 > Candidate  2 = Candidate  3 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 = Candidate  9 * 1
            Candidate 10 > Candidate  4 > Candidate  1 > Candidate  9 > Candidate  5 > Candidate  3 > Candidate  2 > Candidate  6 > Candidate  7 > Candidate  8 * 1
            Candidate 10 > Candidate  4 > Candidate  9 > Candidate  1 > Candidate  3 > Candidate  2 > Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate 10 > Candidate  7 > Candidate  8 > Candidate  4 > Candidate  5 > Candidate  1 > Candidate  2 > Candidate  9 > Candidate  6 > Candidate  3 * 1
            Candidate 10 > Candidate  9 > Candidate  2 > Candidate  1 = Candidate  3 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  7 = Candidate  8 * 1
            Candidate 10 > Candidate  9 > Candidate  4 > Candidate  8 > Candidate  6 > Candidate  3 > Candidate  1 = Candidate  2 = Candidate  5 = Candidate  7 * 1
            Candidate 10 > Candidate  9 > Candidate  7 > Candidate  3 > Candidate  2 > Candidate  1 = Candidate  4 = Candidate  5 = Candidate  6 = Candidate  8 * 1
            EOD,
            $election->getVotesListAsString()
        );

        self::assertSame('Candidate  1 > Candidate  9 > Candidate  8', $election->getResult('STV')->getResultAsString());
    }

    public function testBugDavidHillRandomOrderAndStatsRound (): void
    {
        $hil = new DavidHillFormat(__DIR__.'/TidemanData/A60.HIL');

        self::assertEquals([0=>"1",1=>"2",2=>"3",3=>"4",4=>"5",5=>"6"], $hil->candidates); # Candidates are object, AssertEquals compare __toString

        $implicitElectionFromHill = $hil->setDataToAnElection();

        // Without aggregate vote
        $file = new \SplTempFileObject();
        $file->fwrite(CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $implicitElectionFromHill, aggregateVotes:false));
        $implicitElectionFromCondorcetElection = (new CondorcetElectionFormat($file))->setDataToAnElection();

        self::assertEquals($implicitElectionFromHill->getCandidatesListAsString(), $implicitElectionFromCondorcetElection->getCandidatesListAsString());

        foreach (Condorcet::getAuthMethods() as $method) :
            // Stats
            self::assertSame($implicitElectionFromHill->getResult($method)->getStats(), $implicitElectionFromCondorcetElection->getResult($method)->getStats(), 'Method: '.$method);

            // Result
            self::assertSame($implicitElectionFromHill->getResult($method)->getResultAsString(), $implicitElectionFromCondorcetElection->getResult($method)->getResultAsString(), 'Method: '.$method);
        endforeach;


        // With aggregate vote
        $file = new \SplTempFileObject();
        $file->fwrite(CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $implicitElectionFromHill, aggregateVotes:true));
        $implicitElectionFromCondorcetElection = (new CondorcetElectionFormat($file))->setDataToAnElection();

        self::assertEquals($implicitElectionFromHill->getCandidatesListAsString(), $implicitElectionFromCondorcetElection->getCandidatesListAsString());

        foreach (Condorcet::getAuthMethods() as $method) :
            // Stats
            self::assertEqualsWithDelta(
                $implicitElectionFromHill->getResult($method)->getStats(),
                $implicitElectionFromCondorcetElection->getResult($method)->getStats(),
                1 / (0.1 ** Condorcet::getMethodClass($method)::DECIMAL_PRECISION),
                'Method: '.$method
            );

            // Result
            self::assertSame($implicitElectionFromHill->getResult($method)->getResultAsString(), $implicitElectionFromCondorcetElection->getResult($method)->getResultAsString(), 'Method: '.$method);
        endforeach;

    }
}