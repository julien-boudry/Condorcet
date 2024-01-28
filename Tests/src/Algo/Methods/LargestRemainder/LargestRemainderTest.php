<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Algo\Methods\HighestAverage;

use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\TestCase;

class LargestRemainderTest extends TestCase
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

    # https://en.wikipedia.org/wiki/Largest_remainder_method
    public function testResult_1(): void
    {
        $this->election->parseCandidates('Yellows;Whites;Reds;Greens;Blues;Pinks');

        $this->election->setNumberOfSeats(10);
        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('Yellows ^47000;Whites ^16000;Reds ^15800;Greens ^12000;Blues ^6100;Pinks ^3100');

        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE); // Hare-LR
        expect($this->election->getResult('LR')->getStats()['Seats per Candidates'])->toBe([
            'Yellows' => 5,
            'Whites' => 2,
            'Reds' => 1,
            'Greens' => 1,
            'Blues' => 1,
            'Pinks' => 0, ]);

        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::DROOP); // Hare-LR
        expect($this->election->getResult('LR')->getStats()['Seats per Candidates'])->toBe([
            'Yellows' => 5,
            'Whites' => 2,
            'Reds' => 2,
            'Greens' => 1,
            'Blues' => 0,
            'Pinks' => 0, ]);
    }

    # https://en.wikipedia.org/wiki/Largest_remainder_method
    public function testResult_2(): void
    {
        $this->election->parseCandidates('A;B;C;D;E;F');

        $this->election->setNumberOfSeats(25);
        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('A ^1500;B ^1500;C ^900;D^500;E ^500;F ^200');

        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE); // Hare-LR
        expect($this->election->getResult('LR')->getStats()['Seats per Candidates'])->toBe([
            'A' => 7,
            'B' => 7,
            'C' => 4,
            'D' => 3,
            'E' => 3,
            'F' => 1, ]);
    }

    # https://en.wikipedia.org/wiki/Largest_remainder_method
    public function testResult_3(): void
    {
        $this->election->parseCandidates('A;B;C;D;E;F');

        $this->election->setNumberOfSeats(26);
        $this->election->allowsVoteWeight(true);

        $this->election->parseVotes('A ^1500;B ^1500;C ^900;D^500;E ^500;F ^200');

        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::HARE); // Hare-LR
        expect($this->election->getResult('LR')->getStats()['Seats per Candidates'])->toBe([
            'A' => 8,
            'B' => 8,
            'C' => 5,
            'D' => 2,
            'E' => 2,
            'F' => 1, ]);
    }

    // Fixing error with Droop Quotas in some cases
    public function testResult_4_LR(): void
    {
        $this->election->parseCandidates('A;B;C');
        $this->election->setNumberOfSeats(99);

        $this->election->parseVotes('A>B>C;C>B>A;B>A>C');

        $this->election->setMethodOption('LargestRemainder', 'Quota', StvQuotas::DROOP); // Hare-LR
        expect($this->election->getResult('LR')->getStats()['Seats per Candidates'])->toBe([
            'A' => 33,
            'B' => 33,
            'C' => 33,
        ]);
    }
}
