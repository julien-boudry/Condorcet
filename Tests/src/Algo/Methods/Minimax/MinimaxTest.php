<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\Minimax;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class MinimaxTest extends TestCase
{
    private readonly Election $election;

    protected function setUp(): void
    {
        $this->election = new Election;
    }

    public function testResult_1(): void
    {
        # From https://en.wikipedia.org/wiki/Minimax_Condorcet

        $this->election->addCandidate('Memphis');
        $this->election->addCandidate('Nashville');
        $this->election->addCandidate('Chattanooga');
        $this->election->addCandidate('Knoxville');

        $this->election->parseVotes('
            Memphis > Nashville > Chattanooga * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

        expect($this->election->getWinner('Minimax Winning'))->toBe($this->election->getWinner());

        expect($this->election->getWinner('Minimax Margin'))->toBe($this->election->getWinner());

        expect($this->election->getWinner('Minimax Opposition'))->toBe($this->election->getWinner());

        $expectedRanking = [
            1 => 'Nashville',
            2 => 'Memphis',
            3 => 'Chattanooga',
            4 => 'Knoxville',
        ];

        expect($this->election->getResult('Minimax Winning')->getResultAsArray(true))->toBe($expectedRanking);

        expect($this->election->getResult('Minimax Margin')->getResultAsArray(true))->toBe($expectedRanking);

        expect($this->election->getResult('Minimax Opposition')->getResultAsArray(true))->toBe($expectedRanking);

        expect($this->election->getResult('Minimax Winning')->getStats())->toBe(['Memphis'       =>  ['worst_pairwise_defeat_winning' => 58],
            'Nashville'     =>  ['worst_pairwise_defeat_winning' => 0],
            'Chattanooga'   =>  ['worst_pairwise_defeat_winning' => 68],
            'Knoxville'     =>  ['worst_pairwise_defeat_winning' => 83], ]);

        expect($this->election->getResult('Minimax Margin')->getStats())->toBe(['Memphis'       =>  ['worst_pairwise_defeat_margin' => 16],
            'Nashville'     =>  ['worst_pairwise_defeat_margin' => -16],
            'Chattanooga'   =>  ['worst_pairwise_defeat_margin' => 36],
            'Knoxville'     =>  ['worst_pairwise_defeat_margin' => 66], ]);

        expect($this->election->getResult('Minimax Opposition')->getStats())->toBe(['Memphis'       =>  ['worst_pairwise_opposition' => 58],
            'Nashville'     =>  ['worst_pairwise_opposition' => 42],
            'Chattanooga'   =>  ['worst_pairwise_opposition' => 68],
            'Knoxville'     =>  ['worst_pairwise_opposition' => 83], ]);
    }

    public function testResult_2(): void
    {
        # From https://en.wikipedia.org/wiki/Minimax_Condorcet

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A = C > B * 4
            A > C > B * 47
            C > B > A * 43
            B > A = C * 6
        ');

        expect($this->election->getWinner('Minimax Winning'))->toBe($this->election->getWinner());

        expect($this->election->getWinner('Minimax Margin'))->toBe($this->election->getWinner());

        expect($this->election->getWinner('Minimax Opposition'))->toEqual('C');

        $expectedRanking1 = [
            1 => 'A',
            2 => 'C',
            3 => 'B',
        ];

        expect($this->election->getResult('Minimax Winning')->getResultAsArray(true))->toBe($expectedRanking1);

        expect($this->election->getResult('Minimax Margin')->getResultAsArray(true))->toBe($expectedRanking1);

        expect($this->election->getResult('Minimax Opposition')->getResultAsArray(true))->toBe([1 => 'C',
            2 => 'A',
            3 => 'B', ]);

        expect($this->election->getResult('Minimax Winning')->getStats())->toBe(['A'       =>  ['worst_pairwise_defeat_winning' => 0],
            'B'     =>  ['worst_pairwise_defeat_winning' => 94],
            'C'     =>  ['worst_pairwise_defeat_winning' => 47], ]);

        expect($this->election->getResult('Minimax Margin')->getStats())->toBe(['A'       =>  ['worst_pairwise_defeat_margin' => -2],
            'B'     =>  ['worst_pairwise_defeat_margin' => 88],
            'C'     =>  ['worst_pairwise_defeat_margin' => 4], ]);

        expect($this->election->getResult('Minimax Opposition')->getStats())->toBe(['A'       =>  ['worst_pairwise_opposition' => 49],
            'B'     =>  ['worst_pairwise_opposition' => 94],
            'C'     =>  ['worst_pairwise_opposition' => 47], ]);
    }

    public function testResult_3(): void
    {
        # From http://www.cs.wustl.edu/~legrand/rbvote/desc.html

        $this->election->addCandidate('Abby');
        $this->election->addCandidate('Brad');
        $this->election->addCandidate('Cora');
        $this->election->addCandidate('Dave');
        $this->election->addCandidate('Erin');

        $this->election->parseVotes('
            Abby>Cora>Erin>Dave>Brad * 98
            Brad>Abby>Erin>Cora>Dave * 64
            Brad>Abby>Erin>Dave>Cora * 12
            Brad>Erin>Abby>Cora>Dave * 98
            Brad>Erin>Abby>Dave>Cora * 13
            Brad>Erin>Dave>Abby>Cora * 125
            Cora>Abby>Erin>Dave>Brad * 124
            Cora>Erin>Abby>Dave>Brad * 76
            Dave>Abby>Brad>Erin>Cora * 21
            Dave>Brad>Abby>Erin>Cora * 30
            Dave>Brad>Erin>Cora>Abby * 98
            Dave>Cora>Abby>Brad>Erin * 139
            Dave>Cora>Brad>Abby>Erin * 23
        ');

        expect($this->election->getWinner('Minimax Winning'))->toEqual('Cora');
    }

    public function testResult_4(): void
    {
        # From https://en.wikipedia.org/wiki/Condorcet_loser_criterion

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('L');

        $this->election->parseVotes('
            A > B > C * 1
            A > B > L * 1
            B > C > A * 3
            C > L > A * 1
            L > A > B * 1
            L > C > A * 2
        ');

        expect($this->election->getWinner('Minimax Winning'))->toEqual('L');
    }

    public function testResult_5(): void
    {
        # From https://en.wikipedia.org/wiki/Condorcet_loser_criterion

        $this->election->setImplicitRanking(false);

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');
        $this->election->addCandidate('D');

        $this->election->parseVotes('
            A > C > B > D * 30
            D > B > A > C * 15
            D > B > C > A * 14
            B > C > A > D * 6
            D > C > A = B * 4
            C > A = B * 16
            B > C * 14
            C > A * 3
        ');

        expect($this->election->getWinner('Minimax Winning'))->toEqual('A');
        expect($this->election->getWinner('Minimax Margin'))->toEqual('B');
        expect($this->election->getWinner('Minimax Opposition'))->toEqual('D');

        expect($this->election->getResult('Minimax Winning')->getStats())->toBe(['A'       =>  ['worst_pairwise_defeat_winning' => 35],
            'B'     =>  ['worst_pairwise_defeat_winning' => 50],
            'C'   =>  ['worst_pairwise_defeat_winning' => 45],
            'D'     =>  ['worst_pairwise_defeat_winning' => 36], ]);
        expect($this->election->getResult('Minimax Margin')->getStats())->toBe(['A'       =>  ['worst_pairwise_defeat_margin' => 5],
            'B'     =>  ['worst_pairwise_defeat_margin' => 1],
            'C'   =>  ['worst_pairwise_defeat_margin' => 2],
            'D'     =>  ['worst_pairwise_defeat_margin' => 3], ]);
        expect($this->election->getResult('Minimax Opposition')->getStats())->toBe(['A'       =>  ['worst_pairwise_opposition' => 43],
            'B'     =>  ['worst_pairwise_opposition' => 50],
            'C'   =>  ['worst_pairwise_opposition' => 49],
            'D'     =>  ['worst_pairwise_opposition' => 36], ]);

        // Implicit Ranking
        $this->election->setImplicitRanking(true);

        expect($this->election->getWinner('Minimax Winning'))->not()->toEqual('A');
    }

    public function testResult_6(): void
    {
        # From https://en.wikipedia.org/wiki/Minimax_Condorcet

        $this->election->addCandidate('A');
        $this->election->addCandidate('B');
        $this->election->addCandidate('C');

        $this->election->parseVotes('
            A > B > C
            C > B > A
        ');

        expect($this->election->getWinner('Minimax Margin'))->not()->toBeNull();
    }
}
