<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Converters\CondorcetElectionFormat;
use PHPUnit\Framework\TestCase;

class CondorcetElectionFormatTest extends TestCase
{
    public function testCondorcetElectionFormat1_Simple (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            Richard Boháč>Petr Němec ^42
                            CVOTES);

        $condorcetFormat = new CondorcetElectionFormat($file);
        self::assertSame(false, $condorcetFormat->CandidatesParsedFromVotes);


        $election = $condorcetFormat->setDataToAnElection();

        self::assertFalse($election->isVoteWeightAllowed());
        $election->allowsVoteWeight(true);
        self::assertTrue($election->isVoteWeightAllowed());


        self::assertSame(['Petr Němec','Richard Boháč','Simona Slaná'], $election->getCandidatesListAsString());

        self::assertSame(100, $election->getNumberOfSeats());
        self::assertTrue($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > Petr Němec > Simona Slaná ^42', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }


    public function testCondorcetElectionFormat2_MultiplesErrorsAndComplications (): void
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

        $condorcetFormat = new CondorcetElectionFormat($file);

        $election = $condorcetFormat->setDataToAnElection();

        self::assertSame(['A','B','C'], $election->getCandidatesListAsString());

        self::assertSame(6, $election->getNumberOfSeats());

        self::assertFalse($election->getImplicitRankingRule());

        self::assertSame(10, $election->countVotes());

        self::assertSame(3, $condorcetFormat->invalidBlocksCount);

        self::assertSame(['tag1'] ,$election->getVotesList()[5]->getTags());
    }

    public function testCondorcetElectionFormat3_CustomElection1 (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            #/Number of Seats: 42
                            #/Implicit Ranking: true
                            Richard Boháč>Petr Němec ^42
                            CVOTES);

        $condorcetFormat = new CondorcetElectionFormat($file);

        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(66);

        $condorcetFormat->setDataToAnElection($election);

        self::assertSame(['Petr Němec','Richard Boháč','Simona Slaná'], $election->getCandidatesListAsString());

        self::assertSame(42, $election->getNumberOfSeats());
        self::assertTrue($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > Petr Němec > Simona Slaná', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }

    public function testCondorcetElectionFormat4_CustomElection2 (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            #/Weight allowed: true
                            Richard Boháč>Petr Němec ^42
                            CVOTES);

        $condorcetFormat = new CondorcetElectionFormat($file);

        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(66);
        $election->allowsVoteWeight(false);

        $condorcetFormat->setDataToAnElection($election);

        $election->allowsVoteWeight(true); // Must be forced by parameter


        self::assertSame(['Petr Němec','Richard Boháč','Simona Slaná'], $election->getCandidatesListAsString());

        self::assertSame(66, $election->getNumberOfSeats());
        self::assertFalse($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > Petr Němec ^42', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }

    public function testCondorcetElectionFormat5_UnknowParametersAndEmptyLinesAndCase (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            #/Candidates: Richard Boháč ; 郝文彦  ; Simona Slaná

                            #/AnewParameters: 7
                            #/numBer of Seats: 42
                            #/implicit ranking: true



                                 Richard Boháč>郝文彦 ^42
                            CVOTES);

        $condorcetFormat = new CondorcetElectionFormat($file);

        $election = new Election;
        $election->setImplicitRanking(false);
        $election->setNumberOfSeats(66);

        $condorcetFormat->setDataToAnElection($election);

        self::assertSame(['Richard Boháč','Simona Slaná','郝文彦'], $election->getCandidatesListAsString());

        self::assertSame(42, $election->getNumberOfSeats());
        self::assertTrue($election->getImplicitRankingRule());

        self::assertSame(1, $election->countVotes());
        self::assertSame('Richard Boháč > 郝文彦 > Simona Slaná', $election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true));
        self::assertSame(0, $condorcetFormat->invalidBlocksCount);
    }

    public function testOfficialSpecificationValidExamples (): void
    {
        # Example with tags and implicit ranking
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            # My beautiful election
                            #/Candidates: Candidate A;Candidate B;Candidate C
                            #/Implicit Ranking: true
                            #/Weight allowed: true

                            # Here the votes datas:
                            Candidate A > Candidate B > Candidate C * 42
                            julien@condorcet.vote , signature:55073db57b0a859911 || Candidate A > Candidate B > Candidate C # Same as above, so there will be 43 votes with this ranking. And tags are registered by the software if able.
                            Candidate C > Candidate A = Candidate B ^7 * 8 # 8 votes with a weight of 7.
                            Candidate B = Candidate A > Candidate C
                            Candidate C # Interpreted as Candidate C > Candidate A = Candidate B, because implicit ranking is true (wich is also default, but it's better to say it)
                            Candidate B > Candidate C # Interpreted as Candidate B > Candidate C
                            CVOTES);

        $condorcetFormat = new CondorcetElectionFormat($file);
        $election = $condorcetFormat->setDataToAnElection();

        self::assertSame(54, $election->countVotes());

        self::assertSame(   <<<VOTES
                            Candidate C > Candidate A = Candidate B ^7 * 8
                            Candidate A > Candidate B > Candidate C * 43
                            Candidate A = Candidate B > Candidate C * 1
                            Candidate B > Candidate C > Candidate A * 1
                            Candidate C > Candidate A = Candidate B * 1
                            VOTES
                            , $election->getVotesListAsString());


        self::assertCount(1, $election->getVotesList(tags: 'signature:55073db57b0a859911', with: true));
        self::assertCount(1, $election->getVotesList(tags: 'julien@condorcet.vote', with: true));
        self::assertCount(0, $election->getVotesList(tags: 'otherTag', with: true));
        self::assertSame('Candidate A > Candidate B > Candidate C', current($election->getVotesList(tags: 'julien@condorcet.vote', with: true))->getSimpleRanking());


        # Example without implicit ranking as weight
        $file = new \SplTempFileObject();
        $file->fwrite(      <<<'CVOTES'
                            # My beautiful election
                            #/Candidates: Candidate A ; Candidate B ; Candidate C
                            #/Implicit Ranking: false
                            #/Weight allowed: false

                            # Here the votes datas:
                            Candidate A > Candidate B > Candidate C ^7 *2 # Vote weight is disable, so ^7 is ignored. Two vote with weight of 1.
                            Candidate C>Candidate B # Vote is untouched. When compute pairwise, Candidate C win again Candidate B, no one beats the candidate or achieves a draw.
                            Candidate B # Vote is valid, but not have any effect on most election method, especially Condorcet methods.
                            CVOTES);

        $condorcetFormat = new CondorcetElectionFormat($file);
        $election = $condorcetFormat->setDataToAnElection();

        self::assertSame(4, $election->countVotes());

        self::assertSame(   <<<VOTES
                            Candidate A > Candidate B > Candidate C * 2
                            Candidate B * 1
                            Candidate C > Candidate B * 1
                            VOTES
                            , $election->getVotesListAsString());
    }

    public function testexportElectionToCondorcetElectionFormat (): void
    {
        $input = new \SplTempFileObject();
        $input->fwrite(      <<<'CVOTES'
                            #/Weight allowed: true
                            #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
                            #/Number of Seats: 42
                            #/Implicit Ranking: true

                            Richard Boháč>Petr Němec ^7
                            Richard Boháč>Petr Němec
                            tag1 ,  tag b || Richard Boháč>Petr Němec
                            Simona Slaná * 2
                            Petr Němec *1
                            CVOTES);

        $election = (new CondorcetElectionFormat($input))->setDataToAnElection();

        self::assertSame(
            $assertion1 =
            <<<CVOTES
            #/Candidates: Petr Němec ; Richard Boháč ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: true
            #/Weight Allowed: true

            Richard Boháč > Petr Němec ^7 * 1
            Richard Boháč > Petr Němec * 2
            Simona Slaná * 2
            Petr Němec * 1
            CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $election)
        );

        self::assertStringNotContainsString('Number of Seats: 42', CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $election, includeNumberOfSeats: false));

        $election->setImplicitRanking(false);
        self::assertSame(
            <<<CVOTES
            #/Candidates: Petr Němec ; Richard Boháč ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: false
            #/Weight Allowed: true

            Richard Boháč > Petr Němec ^7 * 1
            Richard Boháč > Petr Němec * 2
            Simona Slaná * 2
            Petr Němec * 1
            CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $election)
        );

        self::assertSame(
            <<<CVOTES
            #/Candidates: Petr Němec ; Richard Boháč ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: false
            #/Weight Allowed: true

            Richard Boháč > Petr Němec ^7
            Richard Boháč > Petr Němec
            tag1,tag b || Richard Boháč > Petr Němec
            Simona Slaná
            Simona Slaná
            Petr Němec
            CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $election, aggregateVotes: false)
        );

        self::assertSame(
            $assertion5 =
            <<<CVOTES
            #/Candidates: Petr Němec ; Richard Boháč ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: false
            #/Weight Allowed: true

            Richard Boháč > Petr Němec ^7
            Richard Boháč > Petr Němec
            Richard Boháč > Petr Němec
            Simona Slaná
            Simona Slaná
            Petr Němec
            CVOTES,
            CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $election, aggregateVotes: false, includeTags: false)
        );

        $election->setImplicitRanking(true);
        $output = new \SplTempFileObject();
        self::assertNull(CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $election, file: $output));
        $output->rewind();

        self::assertSame(
            $assertion1,
            $output->fread(2048)
        );

        $election->setImplicitRanking(false);
        $output = new \SplTempFileObject();
        self::assertNull(CondorcetElectionFormat::exportElectionToCondorcetElectionFormat(election: $election, aggregateVotes: false, includeTags: false, file: $output));
        $output->rewind();
        self::assertSame(
            $assertion5,
            $output->fread(2048)
        );
    }

    public function testEmptyRankingImport (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite($input =  <<<CVOTES
                                #/Candidates: A ; B ; C
                                #/Number of Seats: 42
                                #/Implicit Ranking: false
                                #/Weight Allowed: false

                                /EMPTY_RANKING/ * 1
                                D > E * 1
                                CVOTES);

        $cef = new CondorcetElectionFormat ($file);

        $election = $cef->setDataToAnElection();

        self::assertSame("/EMPTY_RANKING/ * 2", $election->getVotesListAsString());
        self::assertSame([], $election->getVotesList()[0]->getRanking());
        self::assertSame($input, CondorcetElectionFormat::exportElectionToCondorcetElectionFormat($election));
    }

    public function testCandidatesFromVotes (): void
    {
        $file = new \SplTempFileObject();
        $file->fwrite($input =  <<<CVOTES
                                #/Number of Seats: 42
                                #/Implicit Ranking: false
                                #/Weight Allowed: false

                                /EMPTY_RANKING/ * 1
                                D > E^7 * 2 # Comment

                                tag1, tag2 ||A= B > C = D > F
                                D > F = A
                                D>A>B>C>E>F
                                CVOTES);

        $cef = new CondorcetElectionFormat ($file);

        self::assertSame(['A', 'B', 'C', 'D', 'E', 'F'] ,$cef->candidates);
        self::assertSame(true, $cef->CandidatesParsedFromVotes);

        $election = $cef->setDataToAnElection();

        self::assertSame(false, $election->getImplicitRankingRule());
        self::assertSame(42, $election->getNumberOfSeats());

        self::assertEquals(['A', 'B', 'C', 'D', 'E', 'F'] ,$election->getCandidatesList());
        self::assertSame('D > A > B > C > E > F' ,$election->getResult()->getResultAsString());
    }
}