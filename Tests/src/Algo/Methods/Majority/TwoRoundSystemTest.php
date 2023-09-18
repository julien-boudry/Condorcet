<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Methods\Tests;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Methods\Majority\MultipleRoundsSystem;
use PHPUnit\Framework\TestCase;

class TwoRoundSystemTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_French2002(): void
    {
        $this->election->allowsVoteWeight(true);
        $this->election->setImplicitRanking(false);

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
            Le Pen > Mégret ^1686
            Bayrou > Chirac ^684
            Laguiller > Besancenot = Gluckstein  > ^572
            Chevènement > Chirac ^533
            Mamère > Jospin > Chirac ^525
            Besancenot > Gluckstein = Laguillier ^425
            Saint-Josse > Chirac > Jospin ^423
            Madelin > Chirac ^391
            Robert Hue > Jospin > Chirac ^337
            Mégret > Le Pen ^234
            Taubira > Jospin > Chirac ^232
            Lepage > Chirac ^188
            Boutin > Chirac ^119
            Gluckstein > Besancenot = Laguillier ^47
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([
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

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
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
        ],
            2 => [
                'Chirac' => 7038,
                'Le Pen' => 1920,
            ],
        ]);
    }

    public function testResult_1(): void
    {
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

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([
            1 => 'B',
            2 => 'A',
            3 => 'D',
            4 => 'C', ]);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'A' => 42,
            'B' => 26,
            'D' => 17,
            'C' => 15,
        ],
            2 => [
                'B' => 58,
                'A' => 42,
            ],
        ]);
    }

    public function testResult_2(): void
    {
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

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([
            1 => 'D',
            2 => 'A',
            3 => 'B',
            4 => 'C', ]);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'A' => 42,
            'D' => 42,
            'B' => 26,
            'C' => 15,
        ],
            2 => [
                'D' => 83,
                'A' => 42,
            ],
        ]);
    }

    public function testResult_3(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A>B>C
            A=C>B
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([
            1 => 'A',
            2 => 'C',
            3 => 'B', ]);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'A' => 1.5,
            'C' => 0.5,
            'B' => 0,
        ],
        ]);
    }

    public function testResult_5(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A>B>C>D * 51
            B>C>D>A * 24
            C>D>B>A * 25
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([
            1 => 'A',
            2 => 'C',
            3 => 'B',
            4 => 'D', ]);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'A' => 51,
            'C' => 25,
            'B' => 24,
            'D' => 0,
        ],
        ]);
    }

    public function testResult_6(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A>B>C>D * 50
            B>C>D>A * 50
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B'], 2 => 'C']);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'A' => 50,
            'B' => 50,
            'C' => 0,
        ],
            2 => [
                'A' => 50,
                'B' => 50,
            ],
        ]);
    }

    public function testResult_7(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');

        $this->election->parseVotes('
            A>B>C>D * 50
            B>C>D>A * 50
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B']]);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'A' => 50,
            'B' => 50,
        ],
        ]);
    }

    public function testResult_8(): void
    {
        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            D>E * 50
            E>D * 50
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B', 'C']]);

        $stats = $this->election->getResult('Two Rounds')->getStats();
        // \array_walk_recursive($stats, function (float &$value): float {
        //     return $value = round($value, 10);
        // });

        expect($stats)->toBe([1 => [
            'A' => round(100 / 3, MultipleRoundsSystem::DECIMAL_PRECISION),
            'B' => round(100 / 3, MultipleRoundsSystem::DECIMAL_PRECISION),
            'C' => round(100 / 3, MultipleRoundsSystem::DECIMAL_PRECISION),
        ],
        ]);

        $this->election->setImplicitRanking(false);

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B', 'C']]);

        expect($this->election->getResult('Two Rounds')->getStats())->toBe([1 => [
            'A' => 0.0,
            'B' => 0.0,
            'C' => 0.0,
        ],
        ]);
    }

    public function testResult_9(): void
    {
        $this->election->allowsVoteWeight(true);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');

        $this->election->parseVotes('
            A>B ^10
            B ^12
            C ^10
            D>E>A>B ^9
            E>B ^5
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E']);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'B' => 12,
            'A' => 10,
            'C' => 10,
            'D' => 9,
            'E' => 5,
        ],
            2 => [
                'A' => 19,
                'B' => 17,
                'C' => 10,
            ],
        ]);

        $this->election->addVote('E>B ^2');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B'], 2 => 'C', 3 => 'D', 4 => 'E']);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'B' => 12,
            'A' => 10,
            'C' => 10,
            'D' => 9,
            'E' => 7,
        ],
            2 => [
                'A' => 19,
                'B' => 19,
                'C' => 10,
            ],
        ]);

        $this->election->addVote('C');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => 'B', 2 => 'C', 3 => 'A', 4 => 'D', 5 => 'E']);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'B' => 12,
            'C' => 11,
            'A' => 10,
            'D' => 9,
            'E' => 7,
        ],
            2 => [
                'B' => 38,
                'C' => 11,
            ],
        ]);
    }

    public function testResult_10(): void
    {
        $this->election->allowsVoteWeight(true);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');
        $this->election->addCandidate('E');

        $this->election->parseVotes('
            A ^10
            B ^10
            C ^10
            D>E ^9
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B', 'C'], 2 => 'D', 3 => 'E']);

        expect($this->election->getResult('Two Rounds')->getStats())->toBe([1 => [
            'A' => (float) 10,
            'B' => (float) 10,
            'C' => (float) 10,
            'D' => (float) 9,
            'E' => (float) 0,
        ],
            2 => [
                'A' => (float) 13,
                'B' => (float) 13,
                'C' => (float) 13,
            ],
        ]);

        $this->election->setImplicitRanking(false);

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B', 'C'], 2 => 'D', 3 => 'E']);

        expect($this->election->getResult('Two Rounds')->getStats())->toEqual([1 => [
            'A' => 10,
            'B' => 10,
            'C' => 10,
            'D' => 9,
            'E' => 0,
        ],
            2 => [
                'A' => 10,
                'B' => 10,
                'C' => 10,
            ],
        ]);
    }

    public function testResult_11(): void
    {
        $this->election->allowsVoteWeight(true);
        $this->election->setImplicitRanking(false);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A ^10
            B ^10
            C ^10
            D>E ^9
        ');

        expect($this->election->getResult('Two Rounds')->getResultAsArray(true))->toBe([1 => ['A', 'B', 'C']]);

        expect($this->election->getResult('Two Rounds')->getStats())->toBe([1 => [
            'A' => (float) 10,
            'B' => (float) 10,
            'C' => (float) 10,
        ],
        ]);
    }
}
