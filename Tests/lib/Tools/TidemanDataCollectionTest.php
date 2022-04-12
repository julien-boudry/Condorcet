<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\TidemanDataCollection;
use PHPUnit\Framework\TestCase;

class TidemanDataCollectionTest extends TestCase
{
    private static TidemanDataCollection $tidemanA77;

    public function setUp(): void
    {
        self::$tidemanA77 ?? (self::$tidemanA77 = new TidemanDataCollection(__DIR__.'/TidemanData/A77.HIL'));
    }

    public function testA77_Without_Implicit (): void
    {
        $election = self::$tidemanA77->setDataToAnElection();

        self::assertSame(213,$election->countVotes());

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

    public function testA77_With_Implicit (): void
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
}