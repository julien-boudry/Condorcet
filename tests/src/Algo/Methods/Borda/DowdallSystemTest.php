<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Algo\Methods\Borda\DowdallSystem;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result 1', function (): void {
    # From https://en.wikipedia.org/wiki/Borda_count
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');
    $this->election->addCandidate('E');
    $this->election->addCandidate('F');

    $this->election->parseVotes('
            A>B>C>D>E>F
        ');

    expect($this->election->getResult('DowdallSystem')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'F', ]);

    expect($this->election->getResult('DowdallSystem')->stats->asArray)
        ->toEqualWithDelta(
            expected: [
                'A' => 1,
                'B' => 1 / 2,
                'C' => 1 / 3,
                'D' => 1 / 4,
                'E' => 1 / 5,
                'F' => 1 / 6],
            delta: 1 / (0.1 ** DowdallSystem::DECIMAL_PRECISION)
        );
});
