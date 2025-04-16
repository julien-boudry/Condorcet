<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result french2002', function (): void {
    $this->election->allowsVoteWeight(true);

    $this->election->addCandidate('Chirac');
    $this->election->addCandidate('Jospin');
    $this->election->addCandidate('Le Pen');
    $this->election->addCandidate('Bayrou');
    $this->election->addCandidate('Laguiller');
    $this->election->addCandidate('Chevènement');
    $this->election->addCandidate('Mamère');
    $this->election->addCandidate('Besancenot');
    $this->election->addCandidate('Saint-Josse');
    $this->election->addCandidate('Madelin');
    $this->election->addCandidate('Robert Hue');
    $this->election->addCandidate('Mégret');
    $this->election->addCandidate('Taubira');
    $this->election->addCandidate('Lepage');
    $this->election->addCandidate('Boutin');
    $this->election->addCandidate('Gluckstein');

    $this->election->parseVotes('
            Chirac > Bayrou = Jospin = Madelin = Chevénement = Mamère = Robert Hue = Taubira = Lepage = Boutin > Saint-Josse ^1988
            Jospin > Chevénement = Taubira = Mamère > Bayrou > Robert Hue > Chirac = Lepage = Boutin > Madelin > Saint-Josse ^ 1618
            Le Pen ^1686
            Bayrou ^684
            Laguiller ^572
            Chevènement ^533
            Mamère ^525
            Besancenot ^425
            Saint-Josse ^423
            Madelin ^391
            Robert Hue ^337
            Mégret > Le Pen ^234
            Taubira ^232
            Lepage ^188
            Boutin ^119
            Gluckstein ^47
        ');

    expect($this->election->getResult('Fptp')->rankingAsArrayString)->toBe([
        1 => 'Chirac',
        2 => 'Le Pen',
        3 => 'Jospin',
        4 => 'Bayrou',
        5 => 'Laguiller',
        6 => 'Chevènement',
        7 => 'Mamère',
        8 => 'Besancenot',
        9 => 'Saint-Josse',
        10 => 'Madelin',
        11 => 'Robert Hue',
        12 => 'Mégret',
        13 => 'Taubira',
        14 => 'Lepage',
        15 => 'Boutin',
        16 => 'Gluckstein',
    ]);

    expect($this->election->getResult('Fptp')->stats->asArray)->toEqual([1 => [
        'Chirac' => 1988,
        'Le Pen' => 1686,
        'Jospin' => 1618,
        'Bayrou' => 684,
        'Laguiller' => 572,
        'Chevènement' => 533,
        'Mamère' => 525,
        'Besancenot' => 425,
        'Saint-Josse' => 423,
        'Madelin' => 391,
        'Robert Hue' => 337,
        'Mégret' => 234,
        'Taubira' => 232,
        'Lepage' => 188,
        'Boutin' => 119,
        'Gluckstein' => 47,
    ]]);
});

test('result 1', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A>B>C>D * 42
            B>C>D>A * 26
            C>D>B>A * 15
            D>C>B>A * 17
        ');

    expect($this->election->getResult('Fptp')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => 'B',
        3 => 'D',
        4 => 'C', ]);

    expect($this->election->getResult('Fptp')->stats->asArray)->toEqual([1 => [
        'A' => 42,
        'B' => 26,
        'D' => 17,
        'C' => 15,
    ]]);
});

test('result 2', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('
            A>B>C>D ^ 42
            B>C>D>A * 26
            C>D>B>A ^ 15
            D>C>B>A * 17
            D>B=C=A ^ 25
        ');

    expect($this->election->getResult('Fptp')->rankingAsArrayString)->toBe([
        1 => ['A', 'D'],
        2 => 'B',
        3 => 'C', ]);

    expect($this->election->getResult('Fptp')->stats->asArray)->toBe([1 => [
        'A' => (float) 42,
        'D' => (float) 42,
        'B' => (float) 26,
        'C' => (float) 15,
    ]]);
});

test('result 3', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');

    $this->election->parseVotes('
            A>B>C
            A=C>B
        ');

    expect($this->election->getResult('Fptp')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => 'C',
        3 => 'B', ]);

    expect($this->election->getResult('Fptp')->stats->asArray)->toEqual([1 => [
        'A' => 1 + 1 / 2,
        'C' => 1 / 2,
        'B' => 0,
    ]]);
});
