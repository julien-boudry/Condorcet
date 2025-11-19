<?php declare(strict_types=1);


use CondorcetPHP\Condorcet\{Condorcet, Election};

test('empty relevant votes, one candidate', function (string $method): void {
    $election = new Election;
    $election->setSeatsToElect(1);

    $candidateD = $election->addCandidate('D');
    $election->addVote('A > B > C');

    expect($election->getResult($method)->ranking)->toBe([1 => [$candidateD]]);
})->with(Condorcet::getAuthMethods());

// https://electowiki.org/wiki/Beatpath_example_12 > examples fixed
test('example with 12 candidates from electowiki', function (): void {
    $election = new Election;
    $election->authorizeVoteWeight = true;
    do {
        $election->addCandidate();
    } while ($election->countCandidates() < 12);

    $election->parseVotes('
        A>B ^6
        A>C>H>D>B>G>F>E>L>I>K>J ^1
        C>D ^6
        E>A>C>H>D>B ^3
        E>B>D>C>A ^3
        E>F>G>H>A>B ^2
        E>G>F>H>B>C ^2
        F>D>G>H>A ^4
        F>G>E>H>B>D>C>A>L ^1
        G>B>F>H>C ^4
        H>G>F>E>D>C>B>J>I>K>L>A ^1
        H>G>F>E>D>C>B>L>I>J>K>A ^9
        K>J>I>L>A>B>C>D>E>F>G>H ^10
    ');

    expect($election->getCondorcetWinner())->toBeNull();

    expect($election->getResult('Smith set')->rankingAsArrayString)->toBe(
        [
            1 => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'],
        ]
    );

    expect($election->getResult('Schwartz set')->rankingAsArrayString)
        ->toHaveCount(2)
        ->toBe([
            1 => ['A', 'B', 'C', 'D', 'E'],
            2 => ['F', 'G', 'H', 'I', 'J', 'K', 'L'],
        ]);


    // expect($election->getWinner('Schulze'))->toBe('B');;
});
