<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException;
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;

test('condorcet election format1 simple', function (): void {
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
        #/Candidates: Richard Boháč ; Petr Němec ; Simona Slaná
        Richard Boháč>Petr Němec ^42
        CVOTES);

    $condorcetFormat = new CondorcetElectionFormat($file);
    expect($condorcetFormat->CandidatesParsedFromVotes)->toBeFalse();

    $election = $condorcetFormat->setDataToAnElection();

    expect($election->isVoteWeightAllowed())->toBeFalse()->toBe($election->voteWeightAllowed);
    $election->allowsVoteWeight(true);
    expect($election->isVoteWeightAllowed())->toBeTrue()->toBe($election->voteWeightAllowed);

    expect($election->getCandidatesListAsString())->toBe(['Petr Němec', 'Richard Boháč', 'Simona Slaná']);

    expect($election->seatsToElect)->toBe(100);
    expect($election->implicitRankingRule)->toBeTrue()->toBe($election->implicitRankingRule);

    expect($election->countVotes())->toBe(1);
    expect($election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true))->toBe('Richard Boháč > Petr Němec > Simona Slaná ^42');
    expect($condorcetFormat->invalidBlocksCount)->toBe(0);
});

test('condorcet election format2 multiples errors and complications', function (): void {
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
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

    expect($election->getCandidatesListAsString())->toBe(['A', 'B', 'C']);

    expect($election->seatsToElect)->toBe(6);

    expect($election->implicitRankingRule)->toBeFalse()->toBe($election->implicitRankingRule);

    expect($election->countVotes())->toBe(10);

    expect($condorcetFormat->invalidBlocksCount)->toBe(3);

    expect($election->getVotesList()[5]->tags)->toBe(['tag1']);
});

test('condorcet election format3 custom election1', function (): void {
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
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

    expect($election->getCandidatesListAsString())->toBe(['Petr Němec', 'Richard Boháč', 'Simona Slaná']);

    expect($election->seatsToElect)->toBe(42);
    expect($election->implicitRankingRule)->toBeTrue()->toBe($election->implicitRankingRule);

    expect($election->countVotes())->toBe(1);
    expect($election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true))->toBe('Richard Boháč > Petr Němec > Simona Slaná');
    expect($condorcetFormat->invalidBlocksCount)->toBe(0);
});

test('condorcet election format4 custom election2', function (): void {
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
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

    $election->allowsVoteWeight(true);

    // Must be forced by parameter
    expect($election->getCandidatesListAsString())->toBe(['Petr Němec', 'Richard Boháč', 'Simona Slaná']);

    expect($election->seatsToElect)->toBe(66);
    expect($election->implicitRankingRule)->toBeFalse()->toBe($election->implicitRankingRule);

    expect($election->countVotes())->toBe(1);
    expect($election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true))->toBe('Richard Boháč > Petr Němec ^42');
    expect($condorcetFormat->invalidBlocksCount)->toBe(0);
});

test('condorcet election format5 unknow parameters and empty lines and case', function (): void {
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
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

    expect($election->getCandidatesListAsString())->toBe(['Richard Boháč', 'Simona Slaná', '郝文彦']);

    expect($election->seatsToElect)->toBe(42);
    expect($election->implicitRankingRule)->toBeTrue()->toBe($election->implicitRankingRule);

    expect($election->countVotes())->toBe(1);
    expect($election->getVotesList()[0]->getSimpleRanking(context: $election, displayWeight: true))->toBe('Richard Boháč > 郝文彦 > Simona Slaná');
    expect($condorcetFormat->invalidBlocksCount)->toBe(0);
});

test('official specification valid examples', function (): void {
    # Example with tags and implicit ranking
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
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

    expect($election->countVotes())->toBe(54);

    expect($election->getVotesListAsString())->toBe(
        <<<'VOTES'
            Candidate C > Candidate A = Candidate B ^7 * 8
            Candidate A > Candidate B > Candidate C * 43
            Candidate A = Candidate B > Candidate C * 1
            Candidate B > Candidate C > Candidate A * 1
            Candidate C > Candidate A = Candidate B * 1
            VOTES
    );

    expect($election->getVotesList(tags: 'signature:55073db57b0a859911', with: true))->toHaveCount(1);
    expect($election->getVotesList(tags: 'julien@condorcet.vote', with: true))->toHaveCount(1);
    expect($election->getVotesList(tags: 'otherTag', with: true))->toHaveCount(0);
    expect(current($election->getVotesList(tags: 'julien@condorcet.vote', with: true))->getSimpleRanking())->toBe('Candidate A > Candidate B > Candidate C');

    # Example without implicit ranking as weight
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
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

    expect($election->countVotes())->toBe(4);

    expect($election->getVotesListAsString())->toBe(
        <<<'VOTES'
            Candidate A > Candidate B > Candidate C * 2
            Candidate B * 1
            Candidate C > Candidate B * 1
            VOTES
    );
});

test('create from election', function (): void {
    $input = new SplTempFileObject;
    $input->fwrite(<<<'CVOTES'
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

    $election = new CondorcetElectionFormat($input)->setDataToAnElection();

    expect(CondorcetElectionFormat::createFromElection(election: $election))->toBe(
        $assertion1 =
        <<<'CVOTES'
            #/Candidates: Petr Němec ; Richard Boháč ; Simona Slaná
            #/Number of Seats: 42
            #/Implicit Ranking: true
            #/Weight Allowed: true

            Richard Boháč > Petr Němec ^7 * 1
            Richard Boháč > Petr Němec * 2
            Simona Slaná * 2
            Petr Němec * 1
            CVOTES,
    );

    expect(CondorcetElectionFormat::createFromElection(election: $election, includeNumberOfSeats: false))
        ->not()->toContain('Number of Seats: 42');

    $election->setImplicitRanking(false);
    expect(CondorcetElectionFormat::createFromElection(election: $election))->toBe(<<<'CVOTES'
        #/Candidates: Petr Němec ; Richard Boháč ; Simona Slaná
        #/Number of Seats: 42
        #/Implicit Ranking: false
        #/Weight Allowed: true

        Richard Boháč > Petr Němec ^7 * 1
        Richard Boháč > Petr Němec * 2
        Simona Slaná * 2
        Petr Němec * 1
        CVOTES);

    expect(CondorcetElectionFormat::createFromElection(election: $election, aggregateVotes: false))->toBe(<<<'CVOTES'
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
        CVOTES);

    expect(CondorcetElectionFormat::createFromElection(election: $election, aggregateVotes: false, includeTags: false))
        ->toBe(
            $assertion5 =
            <<<'CVOTES'
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
                CVOTES
        );

    $election->setImplicitRanking(true);
    $output = new SplTempFileObject;
    expect(CondorcetElectionFormat::createFromElection(election: $election, file: $output))->toBeNull();
    $output->rewind();

    expect($output->fread(2048))->toBe($assertion1);

    $election->setImplicitRanking(false);
    $output = new SplTempFileObject;
    expect(CondorcetElectionFormat::createFromElection(election: $election, aggregateVotes: false, includeTags: false, file: $output))->toBeNull();
    $output->rewind();
    expect($output->fread(2048))->toBe($assertion5);
});

test('empty ranking import', function (): void {
    $file = new SplTempFileObject;
    $file->fwrite($input =  <<<'CVOTES'
        #/Candidates: A ; B ; C
        #/Number of Seats: 42
        #/Implicit Ranking: false
        #/Weight Allowed: false

        /EMPTY_RANKING/ * 1
        D > E * 1
        CVOTES);

    $cef = new CondorcetElectionFormat($file);

    $election = $cef->setDataToAnElection();

    expect($election->getVotesListAsString())->toBe('/EMPTY_RANKING/ * 2');
    expect($election->getVotesList()[0]->getRanking())->toBe([]);
    expect(CondorcetElectionFormat::createFromElection($election))->toBe($input);
});

test('candidates from votes', function (): void {
    $cef = CondorcetElectionFormat::createFromString(<<<'CVOTES'
        #/Number of Seats: 42
        #/Implicit Ranking: false
        #/Weight Allowed: false

        /EMPTY_RANKING/ * 1
        D > E^7 * 2 # Comment

        tag1, tag2 ||A= B > C = D > F
        D > F = A
        D>A>B>C>E>F
        CVOTES);

    expect($cef->candidates)->toBe(['A', 'B', 'C', 'D', 'E', 'F']);
    expect($cef->CandidatesParsedFromVotes)->toBeTrue();

    $election = $cef->setDataToAnElection();

    expect($election->implicitRankingRule)->toBeFalse()->toBe($election->implicitRankingRule);
    expect($election->seatsToElect)->toBe(42);

    expect($election->getCandidatesList())->toEqual(['A', 'B', 'C', 'D', 'E', 'F']);
    expect($election->getResult()->rankingAsString)->toBe('D > A > B > C > E > F');
});

test('file does not exists', function (): void {
    $this->expectException(FileDoesNotExistException::class);

    new CondorcetElectionFormat(__DIR__ . 'noFile.txt');
});

test('non standard parameters', function (): void {
    $file = new SplTempFileObject;
    $file->fwrite(<<<'CVOTES'
        #/Number Of Seats: 42
        #/Implicit Ranking: tRue
        #/Weight Allowed: false
        #/ a non standard paRameter  : 7
        #/Candidates: A ; b

        A > b
        #/Weight Allowed: true

        CVOTES);

    $cef = new CondorcetElectionFormat($file);

    expect($cef->numberOfSeats)->toBe(42);
    expect($cef->parameters['Number Of Seats'])->toBe('42');

    expect($cef->implicitRanking)->toBeTrue();
    expect($cef->parameters['Implicit Ranking'])->toBe('tRue');

    expect($cef->voteWeight)->toBeFalse();
    expect($cef->parameters['Weight Allowed'])->toBe('false');

    expect($cef->candidates)->toEqual(['A', 'b']);
    expect($cef->parameters['Candidates'])->toBe('A ; b');

    expect($cef->parameters['a non standard paRameter'])->toBe('7');
});
