<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetFormat;
use PHPUnit\Framework\TestCase;

class CondorcetFormatTest extends TestCase
{
    public function testCondorcetFormat1_Simple (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            Richard Boháč>Petr Němec ^42
                            CVOTES);

        $condorcetFormat = new CondorcetFormat($file);

        $election = $condorcetFormat->setDataToAnElection();

        self::assertFalse($election->isVoteWeightAllowed());
        $election->allowsVoteWeight(true);
        self::assertTrue($election->isVoteWeightAllowed());


        self::assertSame(['Richard Boháč','Petr Němec','Simona Slaná'], $election->getCandidatesListAsString());

        self::assertSame(100, $election->getNumberOfSeats());
        self::assertTrue($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > Petr Němec > Simona Slaná ^42', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }


    public function testCondorcetFormat2_MultiplesErrorsAndComplications (): void
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

        self::assertSame(['tag1'] ,$election->getVotesList()[5]->getTags());
    }

    public function testCondorcetFormat3_CustomElection1 (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            #/Number of Seats: 42
                            #/Implicit Ranking: true
                            Richard Boháč>Petr Němec ^42
                            CVOTES);

        $condorcetFormat = new CondorcetFormat($file);

        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(66);

        $condorcetFormat->setDataToAnElection($election);

        self::assertSame(['Richard Boháč','Petr Němec','Simona Slaná'], $election->getCandidatesListAsString());

        self::assertSame(42, $election->getNumberOfSeats());
        self::assertTrue($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > Petr Němec > Simona Slaná', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }

    public function testCondorcetFormat4_CustomElection2 (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            #/Weight allowed: true
                            Richard Boháč>Petr Němec ^42
                            CVOTES);

        $condorcetFormat = new CondorcetFormat($file);

        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(66);
        $election->allowsVoteWeight(false);

        $condorcetFormat->setDataToAnElection($election);

        $election->allowsVoteWeight(true); // Must be forced by parameter


        self::assertSame(['Richard Boháč','Petr Němec','Simona Slaná'], $election->getCandidatesListAsString());

        self::assertSame(66, $election->getNumberOfSeats());
        self::assertFalse($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > Petr Němec ^42', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }

    public function testCondorcetFormat5_UnknowParametersAndEmptyLinesAndCase (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; 郝文彦  ; Simona Slaná

                            #/AnewParameters: 7
                            #/numBer of Seats: 42
                            #/implicit ranking: true



                                 Richard Boháč>郝文彦 ^42
                            CVOTES);

        $condorcetFormat = new CondorcetFormat($file);

        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(66);

        $condorcetFormat->setDataToAnElection($election);

        self::assertSame(['Richard Boháč','郝文彦','Simona Slaná'], $election->getCandidatesListAsString());

        self::assertSame(42, $election->getNumberOfSeats());
        self::assertTrue($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > 郝文彦 > Simona Slaná', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }

    public function testexportElectionToCondorcetFormat (): void
    {
        $input = new \SplTempFileObject();
        $input->fwrite(      <<<'CVOTES'
                            #/Weight allowed: true
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            #/Number of Seats: 42
                            #/Implicit Ranking: true

                            Richard Boháč>Petr Němec ^7
                            Richard Boháč>Petr Němec
                            tag1 || Richard Boháč>Petr Němec
                            Simona Slaná * 2
                            Petr Němec *1
                            CVOTES);

        $election = (new CondorcetFormat($input))->setDataToAnElection();

        self::assertSame(
            $assertion1 =
            <<<CVOTES
            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: true
            #/Weight allowed: true

            Richard Boháč > Petr Němec ^7 * 1
            Richard Boháč > Petr Němec * 2
            Simona Slaná * 2
            Petr Němec * 1
            CVOTES,
            CondorcetFormat::exportElectionToCondorcetFormat(election: $election)
        );

        self::assertStringNotContainsString('Number of Seats: 42', CondorcetFormat::exportElectionToCondorcetFormat(election: $election, includeNumberOfSeats: false));

        $election->setImplicitRanking(false);
        self::assertSame(
            <<<CVOTES
            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: false
            #/Weight allowed: true

            Richard Boháč > Petr Němec ^7 * 1
            Richard Boháč > Petr Němec * 2
            Simona Slaná * 2
            Petr Němec * 1
            CVOTES,
            CondorcetFormat::exportElectionToCondorcetFormat(election: $election)
        );

        self::assertSame(
            <<<CVOTES
            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: false
            #/Weight allowed: true

            Richard Boháč > Petr Němec ^7
            Richard Boháč > Petr Němec
            tag1 || Richard Boháč > Petr Němec
            Simona Slaná
            Simona Slaná
            Petr Němec
            CVOTES,
            CondorcetFormat::exportElectionToCondorcetFormat(election: $election, aggregateVotes: false)
        );

        self::assertSame(
            $assertion5 =
            <<<CVOTES
            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: false
            #/Weight allowed: true

            Richard Boháč > Petr Němec ^7
            Richard Boháč > Petr Němec
            Richard Boháč > Petr Němec
            Simona Slaná
            Simona Slaná
            Petr Němec
            CVOTES,
            CondorcetFormat::exportElectionToCondorcetFormat(election: $election, aggregateVotes: false, includeTags: false)
        );

        $election->setImplicitRanking(true);
        $output = new \SplTempFileObject();
        self::assertNull(CondorcetFormat::exportElectionToCondorcetFormat(election: $election, file: $output));
        $output->rewind();

        self::assertSame(
            $assertion1,
            $output->fread(2048)
        );

        $election->setImplicitRanking(false);
        $output = new \SplTempFileObject();
        self::assertNull(CondorcetFormat::exportElectionToCondorcetFormat(election: $election, aggregateVotes: false, includeTags: false, file: $output));
        $output->rewind();
        self::assertSame(
            $assertion5,
            $output->fread(2048)
        );
    }
}