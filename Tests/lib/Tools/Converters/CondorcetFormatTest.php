<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Tools\Converters\CondorcetFormat;
use PHPUnit\Framework\TestCase;

class CondorcetFormatTest extends TestCase
{
    public function testCondorcetFormat1_MultiplesErrorsAndComplications (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: A    ;B;C
                            #/Number of Seats:      6
                            #/Implicit Ranking: false

                                            A > B > C
                                        A > B > C * 4;tag1 || A > B > C*4 #Coucou
                            # A > B > C
                                        A < B < C * 10
                                        A > E > A* 3
                                D <> B
                                        A > B > C
                            CVOTES);

        $condorcetFormat = new CondorcetFormat($file);

        $election = $condorcetFormat->setDataToAnElection();

        self::assertSame(['A','B','C'], $election->getCandidatesListAsString());

        self::assertSame(6, $election->getNumberOfSeats());

        self::assertFalse($election->getImplicitRankingRule());

        self::assertSame(10, $election->countVotes());

        self::assertSame(3, $condorcetFormat->invalidBlocksCount);
    }
}