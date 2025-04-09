<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Election;

beforeEach(function (): void {
    $this->election = new Election;
});

test('result 1', function (): void {
    # From http://www.cs.wustl.edu/~legrand/rbvote/desc.html
    $CandidateCora = $this->election->addCandidate('Cora');
    $this->election->addCandidate('Abby');
    $this->election->addCandidate('Brad');
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

    expect($this->election->getWinner('DodgsonTideman'))->toBe($CandidateCora);

    expect($this->election->getResult('DodgsonTideman')->rankingAsArrayString)->toBe([1 => 'Cora',
        2 => 'Abby',
        3 => 'Brad',
        4 => 'Dave',
        5 => 'Erin', ]);

    expect($this->election->getResult('DodgsonTideman')->stats->asArray)->toBe([
        'Cora' => [
            'sum_defeat_margin' => 4,
        ],
        'Abby' => [
            'sum_defeat_margin' => 5,
        ],
        'Brad' => [
            'sum_defeat_margin' => 297,
        ],
        'Dave' => [
            'sum_defeat_margin' => 348,
        ],
        'Erin' => [
            'sum_defeat_margin' => 426,
        ],
    ]);
});

test('result 3', function (): void {
    # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
    # Table 2
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            D>C>A>B*6
            B>C>A>D*6
            C>A>B>D*6
            D>B>C>A*6
            A>B>C>D*6
            A>D>B>C*3
            D>A>B>C*3
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('D');

    expect($this->election->getResult('DodgsonQuick')->stats->asArray)->toBe([
        'D' => 3.0,
        'A' => 6.0,
        'B' => 6.0,
        'C' => 6.0,
    ]);
});

test('result 5', function (): void {
    # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
    # Table 4
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            C>A>D>B*15
            B>D>C>A*9
            A>B>D>C*9
            A>C>B>D*5
            A>B>C>D*5
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('C');

    expect($this->election->getResult('DodgsonQuick')->stats->asArray)->toBe(['C' => 2.0,
        'A' => 3.0,
        'B' => 13.0,
        'D' => 24.0,
    ]);
});

test('result 6', function (): void {
    # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
    # Table 5
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            D>A>B>C*10
            B>C>A>D*8
            C>A>B>D*7
            D>C>A>B*4
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('D');

    expect($this->election->getResult('DodgsonQuick')->stats->asArray)->toBe(['D' => 3.0,
        'C' => 4.0,
        'A' => 5.0,
        'B' => 7.0,
    ]);
});

test('result 7', function (): void {
    # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
    # Table 6
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            C>B>A>D*10
            D>A>C>B*8
            D>B>A>C*7
            B>A>C>D*4
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('D');

    expect($this->election->getResult('DodgsonQuick')->stats->asArray)->toBe(['B' => 5.0,
        'C' => 6.0,
        'A' => 8.0,
    ]);
});

test('result 8', function (): void {
    # From http://dss.in.tum.de/files/brandt-research/dodgson.pdf
    # Table 7
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');

    $this->election->parseVotes('
            A>B>C*5
            B>C>A*4
            C>A>B*3
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('A');

    expect($this->election->getResult('DodgsonQuick')->stats->asArray)->toBe(['A' => 1.0,
        'B' => 2.0,
        'C' => 3.0,
    ]);
});

test('result 10', function (): void {
    # From https://link.springer.com/article/10.1007/s003550000060
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A>B>C>D*21
            C>D>B>A*12
            D>C>A>B*5
            B>D>A>C*12
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('B');

    expect($this->election->getWinner('DodgsonTideman'))->toEqual('B');
});

test('result 11', function (): void {
    # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
    # Figure 2 with Tideman Approximation
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A>B>C>D*3
            D>B>A>C*1
            D>C>A>B*1
            B>D>C>A*1
            C>D>B>A*1
        ');

    expect($this->election->getResult('DodgsonTideman')->stats->asArray['A']['sum_defeat_margin'])->toEqual(1);
    expect($this->election->getResult('DodgsonTideman')->stats->asArray['B']['sum_defeat_margin'])->toEqual(1);
    expect($this->election->getResult('DodgsonTideman')->stats->asArray['C']['sum_defeat_margin'])->toEqual(4);
    expect($this->election->getResult('DodgsonTideman')->stats->asArray['D']['sum_defeat_margin'])->toEqual(2);

    expect($this->election->getResult('DodgsonTideman')->rankingAsArrayString)->toBe([1 => ['A', 'B'],
        2 => 'D',
        3 => 'C',
    ]);
});

test('result 12', function (): void {
    # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
    # Figure 3 with Tideman Approximation
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A>B>C>D*5
            D>C>A>B*6
            C>A>B>D*5
            D>B>C>A*5
            B>C>A>D*4
            D>A>B>C*4
            C>D>A>B*1
            B>A>C>D*1
            B>D>A>C*1
            C>A>B>D*1
            A>D>B>C*1
            C>B>A>D*1
        ');

    expect($this->election->getResult('DodgsonTideman')->stats->asArray['A']['sum_defeat_margin'])->toEqual(11);
    expect($this->election->getResult('DodgsonTideman')->stats->asArray['B']['sum_defeat_margin'])->toEqual(11);
    expect($this->election->getResult('DodgsonTideman')->stats->asArray['C']['sum_defeat_margin'])->toEqual(7);
    expect($this->election->getResult('DodgsonTideman')->stats->asArray['D']['sum_defeat_margin'])->toEqual(3);

    expect($this->election->getWinner('DodgsonTideman'))->toEqual('D');

    expect($this->election->getResult('DodgsonTideman')->rankingAsArrayString)->toBe([1 => 'D',
        2 => 'C',
        3 => ['A', 'B'],
    ]);
});

test('result 13', function (): void {
    # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
    # Figure 4
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');
    $this->election->addCandidate('E');
    $this->election->addCandidate('F');

    $this->election->parseVotes('
            A>B>C>D>E>F*19
            F>A>B>C>D>E*12
            E>D>C>B>F>A*12
            B>A>C>D>E>F*9
            F>E>D>C>B>A*9
            F>B>A>C>D>E*10
            E>D>C>A>F>B*10
            E>B>A>C>D>F*10
            F>D>C>A>E>B*10
            D>B>A>C>E>F*10
            F>E>C>A>D>B*10
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('A');

    expect($this->election->getResult('DodgsonQuick')->stats->asArray)->toBe(['A' => 3.0,
        'B' => 4.0,
        'C' => 20.0,
        'D' => 20.0,
        'E' => 30.0,
        'F' => 30.0,
    ]);
});

test('result 14', function (): void {
    # From https://www.maa.org/sites/default/files/pdf/cmj_ftp/CMJ/September%202010/3%20Articles/6%2009-229%20Ratliff/Dodgson_CMJ_Final.pdf
    # Figure 4: each voters add 4 friends.
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');
    $this->election->addCandidate('E');
    $this->election->addCandidate('F');

    $this->election->parseVotes('
            A>B>C>D>E>F*95
            F>A>B>C>D>E*60
            E>D>C>B>F>A*60
            B>A>C>D>E>F*45
            F>E>D>C>B>A*45
            F>B>A>C>D>E*50
            E>D>C>A>F>B*50
            E>B>A>C>D>F*50
            F>D>C>A>E>B*50
            D>B>A>C>E>F*50
            F>E>C>A>D>B*50
        ');

    expect($this->election->getResult('DodgsonQuick')->stats->asArray['A'])->toEqual(13);

    expect($this->election->getResult('DodgsonQuick')->stats->asArray['B'])->toEqual(12);

    expect($this->election->getWinner('DodgsonQuick'))->toEqual('B');
});

test('result 15', function (): void {
    $this->election->addCandidate('Memphis');
    $this->election->addCandidate('Nashville');
    $this->election->addCandidate('Knoxville');
    $this->election->addCandidate('Chattanooga');

    $this->election->parseVotes('
            Memphis > Chattanooga > Nashville * 42
            Nashville > Chattanooga > Knoxville * 26
            Chattanooga > Knoxville > Nashville * 15
            Knoxville > Chattanooga > Nashville * 17
        ');

    expect($this->election->getWinner('DodgsonQuick'))->toBe($this->election->getWinner(null));
});

test('result 16', function (): void {
    $this->election->addCandidate('A');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('D');

    $this->election->parseVotes('
            A
            B
        ');

    expect($this->election->getResult('DodgsonQuick')->rankingAsArrayString)->toBe([
        1 => ['A', 'B'],
        2 => ['C', 'D'],
    ]);
});
