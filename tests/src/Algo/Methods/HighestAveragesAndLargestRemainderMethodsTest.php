<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});
afterEach(function (): void {
    $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE);
});

test('france legislatives2022 1er tour', function (): void {
    $this->election->setNumberOfSeats(577);
    $this->election->allowsVoteWeight(true);

    $this->election->parseCandidates(
        'Divers extrême gauche
            Parti radical de gauche
            Nouvelle union populaire écologique et sociale
            Divers gauche
            Ecologistes
            Divers
            Régionaliste
            Ensemble ! (Majorité présidentielle)
            Divers centre
            Union des Démocrates et des Indépendants
            Les Républicains
            Divers droite
            Droite souverainiste
            Reconquête !
            Rassemblement National
            Divers extrême droite'
    );

    $this->election->parseVotes('
            Divers extrême gauche	^266412
            Parti radical de gauche	^126689
            Nouvelle union populaire écologique et sociale	^5836079
            Divers gauche	^713574
            Ecologistes	^608314
            Divers	^192624
            Régionaliste	^291384
            Ensemble ! (Majorité présidentielle)	^5857364
            Divers centre	^283612
            Union des Démocrates et des Indépendants	^198062
            Les Républicains	^2370440
            Divers droite	^530782
            Droite souverainiste	^249603
            Reconquête !	^964775
            Rassemblement National	^4248537
            Divers extrême droite	^6457
        ');

    // SainteLeague
    expect($this->election->getResult('SainteLague')->stats['Seats per Candidates'])->toBe([
        'Divers extrême gauche' => 7,
        'Parti radical de gauche' => 3,
        'Nouvelle union populaire écologique et sociale' => 148,
        'Divers gauche' => 18,
        'Ecologistes' => 15,
        'Divers' => 5,
        'Régionaliste' => 7,
        'Ensemble ! (Majorité présidentielle)' => 149,
        'Divers centre' => 7,
        'Union des Démocrates et des Indépendants' => 5,
        'Les Républicains' => 60,
        'Divers droite' => 14,
        'Droite souverainiste' => 6,
        'Reconquête !' => 25,
        'Rassemblement National' => 108,
        'Divers extrême droite' => 0,
    ]);

    expect(array_sum($this->election->getResult('SainteLague')->stats['Seats per Candidates']))->toBe(577);
    expect($this->election->getResult('SainteLague')->rankingAsArray)->toHaveCount(577);

    // Jefferson
    expect($this->election->getResult('Jefferson')->stats['Seats per Candidates'])->toBe([
        'Divers extrême gauche' => 6,
        'Parti radical de gauche' => 3,
        'Nouvelle union populaire écologique et sociale' => 150,
        'Divers gauche' => 18,
        'Ecologistes' => 15,
        'Divers' => 4,
        'Régionaliste' => 7,
        'Ensemble ! (Majorité présidentielle)' => 150,
        'Divers centre' => 7,
        'Union des Démocrates et des Indépendants' => 5,
        'Les Républicains' => 60,
        'Divers droite' => 13,
        'Droite souverainiste' => 6,
        'Reconquête !' => 24,
        'Rassemblement National' => 109,
        'Divers extrême droite' => 0,
    ]);

    expect(array_sum($this->election->getResult('Jefferson')->stats['Seats per Candidates']))->toBe(577);
    expect($this->election->getResult('Jefferson')->rankingAsArray)->toHaveCount(577);

    // Hare-LR
    $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE);

    // Hare-LR
    expect($this->election->getResult('LargestRemainder')->stats['Seats per Candidates'])->toBe([
        'Divers extrême gauche' => 7,
        'Parti radical de gauche' => 3,
        'Nouvelle union populaire écologique et sociale' => 148,
        'Divers gauche' => 18,
        'Ecologistes' => 15,
        'Divers' => 5,
        'Régionaliste' => 7,
        'Ensemble ! (Majorité présidentielle)' => 149,
        'Divers centre' => 7,
        'Union des Démocrates et des Indépendants' => 5,
        'Les Républicains' => 60,
        'Divers droite' => 14,
        'Droite souverainiste' => 6,
        'Reconquête !' => 25,
        'Rassemblement National' => 108,
        'Divers extrême droite' => 0,
    ]);

    expect(array_sum($this->election->getResult('LargestRemainder')->stats['Seats per Candidates']))->toBe(577);
    expect($this->election->getResult('LargestRemainder')->rankingAsArray)->toHaveCount(577);

    // Droop-LR
    $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::DROOP);

    // Droop-LR
    expect($this->election->getResult('LargestRemainder')->stats['Seats per Candidates'])->toBe([
        'Divers extrême gauche' => 7,
        'Parti radical de gauche' => 3,
        'Nouvelle union populaire écologique et sociale' => 148,
        'Divers gauche' => 18,
        'Ecologistes' => 15,
        'Divers' => 5,
        'Régionaliste' => 7,
        'Ensemble ! (Majorité présidentielle)' => 149,
        'Divers centre' => 7,
        'Union des Démocrates et des Indépendants' => 5,
        'Les Républicains' => 60,
        'Divers droite' => 14,
        'Droite souverainiste' => 6,
        'Reconquête !' => 25,
        'Rassemblement National' => 108,
        'Divers extrême droite' => 0,
    ]);

    expect(array_sum($this->election->getResult('LargestRemainder')->stats['Seats per Candidates']))->toBe(577);
    expect($this->election->getResult('LargestRemainder')->rankingAsArray)->toHaveCount(577);

    //  Hagenbach-Bischoff-LR
    $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HAGENBACH_BISCHOFF);
    //  Hagenbach-Bischoff-LR
    expect($this->election->getResult('LargestRemainder')->stats['Seats per Candidates'])->toBe([
        'Divers extrême gauche' => 7,
        'Parti radical de gauche' => 3,
        'Nouvelle union populaire écologique et sociale' => 148,
        'Divers gauche' => 18,
        'Ecologistes' => 15,
        'Divers' => 5,
        'Régionaliste' => 7,
        'Ensemble ! (Majorité présidentielle)' => 149,
        'Divers centre' => 7,
        'Union des Démocrates et des Indépendants' => 5,
        'Les Républicains' => 60,
        'Divers droite' => 14,
        'Droite souverainiste' => 6,
        'Reconquête !' => 25,
        'Rassemblement National' => 108,
        'Divers extrême droite' => 0,
    ]);

    expect(array_sum($this->election->getResult('LargestRemainder')->stats['Seats per Candidates']))->toBe(577);
    expect($this->election->getResult('LargestRemainder')->rankingAsArray)->toHaveCount(577);

    //  Imperiali-LR
    $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::IMPERIALI);
    //  Imperiali-LR
    expect($this->election->getResult('LargestRemainder')->stats['Seats per Candidates'])->toBe([
        'Divers extrême gauche' => 7,
        'Parti radical de gauche' => 3,
        'Nouvelle union populaire écologique et sociale' => 149,
        'Divers gauche' => 18,
        'Ecologistes' => 15,
        'Divers' => 5,
        'Régionaliste' => 7,
        'Ensemble ! (Majorité présidentielle)' => 149,
        'Divers centre' => 7,
        'Union des Démocrates et des Indépendants' => 5,
        'Les Républicains' => 60,
        'Divers droite' => 13,
        'Droite souverainiste' => 6,
        'Reconquête !' => 25,
        'Rassemblement National' => 108,
        'Divers extrême droite' => 0,
    ]);

    expect(array_sum($this->election->getResult('LargestRemainder')->stats['Seats per Candidates']))->toBe(577);
    expect($this->election->getResult('LargestRemainder')->rankingAsArray)->toHaveCount(577);
});

test('result 1', function (): void {
    $this->election->parseCandidates('Con;Lab;LD;Brexit;Ash Ind;Green;Others');
    $this->election->setNumberOfSeats(11);
    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('Con ^258794; Lab ^204011; LD ^33604; Brexit ^15728; Ash Ind ^13498; Green ^10375; Others ^9743');

    expect($this->election->getResult('SainteLague')->rankingAsString)->toBe('Con > Lab > Con > Lab > Con > Lab > Con > LD > Lab > Con > Con');
    expect($this->election->getResult('Jefferson')->rankingAsString)->toBe('Con > Lab > Con > Lab > Con > Lab > Con > Con > Lab > Con > Lab');

    $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE);
    // Hare-LR
    expect($this->election->getResult('LargestRemainder')->rankingAsString)->toBe('Con > Con > Lab > Con > Lab > Con > Lab > Con > Lab > LD > Brexit');
});

test('result 2', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->setNumberOfSeats(8);
    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('A ^100000; B ^80000; C ^30000; D ^20000');

    expect($this->election->getResult('SainteLague')->rankingAsString)->toBe('A > B > A > C > B > A > D > B');
    expect($this->election->getResult('Jefferson')->rankingAsString)->toBe('A > B > A > B > A > C > B > A');
});

test('ties on first rank', function (): void {
    $this->election->setNumberOfSeats(1);

    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');

    $this->election->addVote('A = B > C');
    expect($this->election->getResult('SainteLague')->rankingAsArray)->toBe([]);

    $this->election->addVote('B>A');
    expect($this->election->getResult('SainteLague')->rankingAsString)->toBe('B');
});
