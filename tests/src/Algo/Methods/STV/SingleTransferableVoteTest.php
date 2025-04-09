<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\StvQuotaNotImplementedException;
use CondorcetPHP\Condorcet\Algo\Methods\STV\SingleTransferableVote;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;

beforeEach(function (): void {
    $this->election = new Election;
});
afterEach(function (): void {
    $this->election->setMethodOption('STV', 'Quota', StvQuotas::DROOP);
});

test('quota option', function (): void {
    expect(StvQuotas::make('droop'))->toBe(StvQuotas::DROOP);

    expect($this->election->setMethodOption('STV', 'Quota', StvQuotas::make('Hagenbach-Bischoff')))->toBe($this->election);

    $this->expectException(StvQuotaNotImplementedException::class);
    $this->expectExceptionMessage('This STV quota is not implemented: "another quota"');

    $this->election->setMethodOption('STV', 'Quota', StvQuotas::make('another quota'));
});

test('result 1', function (): void {
    # From https://fr.wikipedia.org/wiki/Scrutin_%C3%A0_vote_unique_transf%C3%A9rable
    $this->election->addCandidate('D');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('A');

    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('
            A>B>C>D ^ 28
            A>C>D>B ^ 14
            B>C>A>D ^ 15
            C>A>B>D ^ 17
            D>B>C>A ^ 26
        ');

    $this->election->setNumberOfSeats(2);

    expect($this->election->getResult('STV')->stats['rounds'])
        ->toEqualWithDelta(
            expected: [
                1 => [
                    'A' => 42.0,
                    'D' => 26.0,
                    'C' => 17.0,
                    'B' => 15.0,
                ],
                2 => [
                    'D' => 26.0,
                    'B' => 20.33333333333,
                    'C' => 19.66666666667,
                ],
                3 => [
                    'B' => 37.33333333333,
                    'D' => 28.66666666667,
                ],
            ],
            delta: 1 / (0.1 ** SingleTransferableVote::DECIMAL_PRECISION)
        );

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->toBe(34.0);

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => 'B',
    ]);
});

test('result 2', function (): void {
    # From https://en.wikipedia.org/wiki/Single_transferable_vote
    $this->election->addCandidate('Orange');
    $this->election->addCandidate('Pear');
    $this->election->addCandidate('Chocolate');
    $this->election->addCandidate('Strawberry');
    $this->election->addCandidate('Hamburger');

    $this->election->setImplicitRanking(false);
    $this->election->allowsVoteWeight(true);

    $this->election->setNumberOfSeats(3);

    $this->election->parseVotes('
            Orange ^ 4
            Pear > Orange * 2
            Chocolate > Strawberry * 8
            Chocolate > Hamburger * 4
            Strawberry
            Hamburger
        ');

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->tobe(6.0);

    expect($this->election->getResult('STV')->stats['rounds'])->toBe([
        1 => [
            'Chocolate' => 12.0,
            'Orange' => 4.0,
            'Pear' => 2.0,
            'Strawberry' => 1.0,
            'Hamburger' => 1.0,
        ],
        2 => [
            'Strawberry' => 5.0,
            'Orange' => 4.0,
            'Hamburger' => 3.0,
            'Pear' => 2.0,
        ],
        3 => [
            'Orange' => 6.0,
            'Strawberry' => 5.0,
            'Hamburger' => 3.0,
        ],
        4 => [
            'Strawberry' => 5.0,
            'Hamburger' => 3.0,
        ],
        5 => [
            'Strawberry' => 5.0,
        ],
    ]);

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'Chocolate',
        2 => 'Orange',
        3 => 'Strawberry',
    ]);
});

test('result 3', function (): void {
    # From https://en.wikipedia.org/wiki/Schulze_STV
    $this->election->addCandidate('Andrea');
    $this->election->addCandidate('Brad');
    $this->election->addCandidate('Carter');

    $this->election->setImplicitRanking(false);
    $this->election->allowsVoteWeight(true);

    $this->election->setNumberOfSeats(2);

    $this->election->parseVotes('
            Andrea > Brad > Carter ^ 12
            Andrea > Carter > Brad ^ 26
            Andrea > Carter > Brad ^ 12
            Carter > Andrea > Brad ^ 13
            Brad ^ 27
        ');

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->toBe(31.0);

    expect($this->election->getResult('STV')->stats['rounds'])->toBe([
        1 => [
            'Andrea' => 50.0,
            'Brad' => 27.0,
            'Carter' => 13.0,
        ],
        2 => [
            'Brad' => 31.56,
            'Carter' => 27.44,
        ],
    ]);

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'Andrea',
        2 => 'Brad',
    ]);

    $this->election->setStatsVerbosity(StatsVerbosity::LOW);
    expect($this->election->getResult('STV')->stats)->not()->toHaveKey('rounds');
});

test('result 4', function (): void {
    # From https://it.wikipedia.org/wiki/Voto_singolo_trasferibile
    $this->election->addCandidate('D');
    $this->election->addCandidate('B');
    $this->election->addCandidate('C');
    $this->election->addCandidate('A');

    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('
            A>D ^ 40
            B>A ^ 10
            B>C ^ 5
            C>B ^ 25
            D>B ^ 20
        ');

    $this->election->setNumberOfSeats(3);

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->tobe(26.0);

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'A',
        2 => 'D',
        3 => 'C',
    ]);
});

test('result alternative quotas1', function (): void {
    # From https://en.wikipedia.org/wiki/Hagenbach-Bischoff_quota
    $this->election->addCandidate('Andrea');
    $this->election->addCandidate('Carter');
    $this->election->addCandidate('Brad');

    $this->election->setImplicitRanking(false);
    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('
            Andrea > Carter ^45
            Carter ^25
            Brad ^30
        ');

    $this->election->setNumberOfSeats(2);
    $this->election->setMethodOption('STV', 'Quota', StvQuotas::make('Hagenbach-Bischoff'));

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->toBe(round(33 + 1 / 3, SingleTransferableVote::DECIMAL_PRECISION, \RoundingMode::HalfTowardsZero));

    expect($this->election->getResult('STV')->stats['rounds'])
        ->toEqualWithDelta(
            expected: [
                1 => [
                    'Andrea' => 45.0,
                    'Brad' => 30.0,
                    'Carter' => 25.0,
                ],
                2 => [
                    'Carter' => 36.0 + 2 / 3,
                    'Brad' => 30.0,
                ],
            ],
            delta: 1 / (0.1 ** SingleTransferableVote::DECIMAL_PRECISION)
        );

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'Andrea',
        2 => 'Carter',
    ]);

    expect(StvQuotas::make('Hagenbach-Bischoff'))->toBe($this->election->getResult('STV')->methodOptions['Quota']);
});

test('result alternative quotas2', function (): void {
    # From https://en.wikipedia.org/wiki/Imperiali_quota
    $this->election->addCandidate('Andrea');
    $this->election->addCandidate('Carter');
    $this->election->addCandidate('Brad');

    $this->election->setImplicitRanking(false);
    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('
            Andrea > Carter ^65
            Carter ^15
            Brad ^20
        ');

    $this->election->setNumberOfSeats(2);
    $this->election->setMethodOption('STV', 'Quota', StvQuotas::IMPERIALI);

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->toBe((float) (100 / (2 + 2)));

    expect($this->election->getResult('STV')->stats['rounds'])->toBe([
        1 => [
            'Andrea' => 65.0,
            'Brad' => 20.0,
            'Carter' => 15.0,
        ],
        2 => [
            'Carter' => 55.0,
            'Brad' => 20.0,
        ],
    ]);

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'Andrea',
        2 => 'Carter',
    ]);

    expect(StvQuotas::make('Imperiali quota'))->toBe($this->election->getResult('STV')->methodOptions['Quota']);
});

test('result alternative quotas3', function (): void {
    # From https://en.wikipedia.org/wiki/Hare_quota
    $this->election->addCandidate('Andrea');
    $this->election->addCandidate('Carter');
    $this->election->addCandidate('Brad');

    $this->election->setImplicitRanking(false);
    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('
            Andrea > Carter ^60
            Carter ^14
            Brad ^26
        ');

    $this->election->setNumberOfSeats(2);
    $this->election->setMethodOption('STV', 'Quota', StvQuotas::make('Hare quota'));

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->toBe((float) (100 / 2));

    expect($this->election->getResult('STV')->stats['rounds'])->toBe([
        1 => [
            'Andrea' => 60.0,
            'Brad' => 26.0,
            'Carter' => 14.0,
        ],
        2 => [
            'Brad' => 26.0,
            'Carter' => 24.0,
        ],
        3 => ['Brad' => 26.0],
    ]);

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'Andrea',
        2 => 'Brad',
    ]);

    expect(StvQuotas::HARE)->toBe($this->election->getResult('STV')->methodOptions['Quota']);
});

test('result alternative quotas4', function (): void {
    # From https://en.wikipedia.org/wiki/CPO-STV
    $this->election->addCandidate('Andrea');
    $this->election->addCandidate('Carter');
    $this->election->addCandidate('Brad');
    $this->election->addCandidate('Delilah');
    $this->election->addCandidate('Scott');

    $this->election->setImplicitRanking(false);
    $this->election->allowsVoteWeight(true);

    $this->election->parseVotes('
            Andrea ^25
            Carter > Brad > Delilah ^34
            Brad > Delilah ^7
            Delilah > Brad ^8
            Delilah > Scott ^5
            Scott > Delilah ^21
        ');

    $this->election->setNumberOfSeats(3);
    $this->election->setMethodOption('STV', 'Quota', StvQuotas::HAGENBACH_BISCHOFF);

    expect($this->election->getResult('STV')->stats['Votes Needed to Win'])->toBe(25.0);

    expect($this->election->getResult('STV')->stats['rounds'])->toBe([
        1 => [
            'Carter' => 34.0,
            'Andrea' => 25.0,
            'Scott' => 21.0,
            'Delilah' => 13.0,
            'Brad' => 7.0,
        ],
        2 => [
            'Scott' => 21.0,
            'Brad' => 16.0,
            'Delilah' => 13.0,
        ],
        3 => [
            'Scott' => 26.0,
            'Brad' => 24.0,
        ],
    ]);

    expect($this->election->getResult('STV')->rankingAsArrayString)->toBe([
        1 => 'Carter',
        2 => 'Andrea',
        3 => 'Scott',
    ]);
});
