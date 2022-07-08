<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\HighestAverage;

use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class HighestAveragesAndLargestRemainderMethodsTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    protected function tearDown(): void
    {
        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE);
    }

    public function testFranceLegislatives2022_1erTour(): void
    {
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
        self::assertSame([
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
        ], $this->election->getResult('SainteLague')->getStats()['Seats per Candidates']);

        $this->assertSame(577, array_sum($this->election->getResult('SainteLague')->getStats()['Seats per Candidates']));
        $this->assertCount(577, $this->election->getResult('SainteLague')->getResultAsArray());

        // Jefferson
        self::assertSame([
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
        ], $this->election->getResult('Jefferson')->getStats()['Seats per Candidates']);

        $this->assertSame(577, array_sum($this->election->getResult('Jefferson')->getStats()['Seats per Candidates']));
        $this->assertCount(577, $this->election->getResult('Jefferson')->getResultAsArray());

        // Hare-LR
        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE); // Hare-LR
        self::assertSame([
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
        ], $this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']);

        $this->assertSame(577, array_sum($this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']));
        $this->assertCount(577, $this->election->getResult('LargestRemainder')->getResultAsArray());

        // Droop-LR
        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::DROOP); // Droop-LR
        self::assertSame([
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
        ], $this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']);

        $this->assertSame(577, array_sum($this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']));
        $this->assertCount(577, $this->election->getResult('LargestRemainder')->getResultAsArray());

        //  Hagenbach-Bischoff-LR
        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HAGENBACH_BISCHOFF); //  Hagenbach-Bischoff-LR
        self::assertSame([
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
        ], $this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']);

        $this->assertSame(577, array_sum($this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']));
        $this->assertCount(577, $this->election->getResult('LargestRemainder')->getResultAsArray());

        //  Imperiali-LR
        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::IMPERIALI); //  Imperiali-LR
        self::assertSame([
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
        ], $this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']);

        $this->assertSame(577, array_sum($this->election->getResult('LargestRemainder')->getStats()['Seats per Candidates']));
        $this->assertCount(577, $this->election->getResult('LargestRemainder')->getResultAsArray());
    }

    # https://www.electoral-reform.org.uk/what-is-the-difference-between-dhondt-sainte-lague-and-hare/
    public function testResult_1(): void
    {
        $this->election->parseCandidates('Con;Lab;LD;Brexit;Ash Ind;Green;Others');
        $this->election->setNumberOfSeats(11);
        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('Con ^258794; Lab ^204011; LD ^33604; Brexit ^15728; Ash Ind ^13498; Green ^10375; Others ^9743');

        self::assertSame('Con > Lab > Con > Lab > Con > Lab > Con > LD > Lab > Con > Con', $this->election->getResult('SainteLague')->getResultAsString());
        self::assertSame('Con > Lab > Con > Lab > Con > Lab > Con > Con > Lab > Con > Lab', $this->election->getResult('Jefferson')->getResultAsString());

        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE); // Hare-LR
        self::assertSame('Con > Con > Lab > Con > Lab > Con > Lab > Con > Lab > LD > Brexit', $this->election->getResult('LargestRemainder')->getResultAsString());
    }

    # https://en.wikipedia.org/wiki/Webster/Sainte-Lagu%C3%AB_method
    # https://en.wikipedia.org/wiki/D%27Hondt_method
    public function testResult_2(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->setNumberOfSeats(8);
        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('A ^100000; B ^80000; C ^30000; D ^20000');

        self::assertSame('A > B > A > C > B > A > D > B', $this->election->getResult('SainteLague')->getResultAsString());
        self::assertSame('A > B > A > B > A > C > B > A', $this->election->getResult('Jefferson')->getResultAsString());
    }

    public function testTiesOnFirstRank(): void
    {
        $this->election->setNumberOfSeats(1);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->addVote('A = B > C');
        self::assertSame([], $this->election->getResult('SainteLague')->getResultAsArray());

        $this->election->addVote('B>A');
        self::assertSame('B', $this->election->getResult('SainteLague')->getResultAsString());
    }
}
