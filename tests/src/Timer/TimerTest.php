<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\{Condorcet, Election};
use CondorcetPHP\Condorcet\Throwable\TimerException;
use CondorcetPHP\Condorcet\Timer\{Chrono, Manager};

beforeEach(function (): void {
    Condorcet::$UseTimer = true;

    $this->election = new Election;
    $this->election->parseCandidates('A;B;C');
});

afterEach(function (): void {
    Condorcet::$UseTimer = new ReflectionClass(Condorcet::class)->getProperty('UseTimer')->getDefaultValue();
});

test('invalid chrono', function (): void {
    $manager1 = new Manager;
    $manager2 = new Manager;

    $chrono1 = new Chrono($manager1);
    $chrono2 = new Chrono($manager2);

    $manager1->addTime($chrono1);

    $this->expectException(TimerException::class);
    $this->expectExceptionMessage('Only a chrono linked to this manager can be used');

    $manager1->addTime($chrono2);
});

test('getLastTimer return value', function (): void {
    Condorcet::$UseTimer = false; // reset to false before adding votes

    $election = new Election;
    $election->parseCandidates('A;B;C');
    $election->parseVotes('A>B>C');

    Condorcet::$UseTimer = true; // declared after pairwise was already computed during voting

    $election->getPairwise();
    expect($election->getLastTimer())->toBeNull(); // Pairwise was computed during voting
});

// -------
// History tests
// -------

test('history is empty before any computation', function (): void {
    expect($this->election->getTimerManager()->getHistory())->toBeEmpty();
    expect($this->election->getGlobalTimer())->toBe(0.0);
});

test('history is empty when timer is disabled', function (): void {
    Condorcet::$UseTimer = false;

    $election = new Election;
    $election->parseCandidates('A;B;C');
    $election->parseVotes('A>B>C');
    $election->getResult('Schulze');

    expect($election->getTimerManager()->getHistory())->toBeEmpty();
    expect($election->getGlobalTimer())->toBe(0.0);
});

test('history entries have valid structure', function (): void {
    $this->election->parseVotes('A>B>C');
    $this->election->getResult('Schulze');

    $history = $this->election->getTimerManager()->getHistory();

    expect($history)->not()->toBeEmpty();

    foreach ($history as $entry) {
        expect($entry)->toHaveKeys(['role', 'process_in', 'timer_start', 'timer_end']);
        expect($entry['process_in'])->toBeFloat()->toBeGreaterThan(0.0);
        expect($entry['timer_start'])->toBeFloat()->toBeGreaterThan(0.0);
        expect($entry['timer_end'])->toBeFloat()->toBeGreaterThanOrEqual($entry['timer_start']);
    }
});

test('globalTimer equals the span from first chrono start to last chrono end', function (): void {
    $this->election->parseVotes('A>B>C');
    $this->election->getResult('Schulze');

    $history = $this->election->getTimerManager()->getHistory();

    expect($this->election->getGlobalTimer())->toBeGreaterThan(0.0);

    // globalTimer = last_end - startDeclare.
    // startDeclare is the start of the very first chrono (history[0].timer_start).
    // The last chrono to destruct is always the outermost one (history last entry).
    $expectedGlobalTimer = end($history)['timer_end'] - $history[0]['timer_start'];
    expect($this->election->getGlobalTimer())->toEqualWithDelta($expectedGlobalTimer, 1e-9);

    // Each individual process_in fits within the global span (no entry can be longer than total).
    foreach ($history as $entry) {
        expect($entry['process_in'])->toBeLessThanOrEqual($this->election->getGlobalTimer() + 1e-9);
    }
});

test('history contains Do Pairwise entry on first vote', function (): void {
    $this->election->parseVotes('A>B>C');

    $roles = array_column($this->election->getTimerManager()->getHistory(), 'role');
    expect($roles)->toContain('Do Pairwise');
});

test('history contains Add Vote To Pairwise on incremental vote addition', function (): void {
    $this->election->parseVotes('A>B>C'); // first vote → triggers doPairwise, not addNewVote

    $this->election->addVote('B>C>A'); // incremental addition
    $this->election->addVote('C>A>B');

    $roles = array_column($this->election->getTimerManager()->getHistory(), 'role');
    expect(array_count_values($roles)['Add Vote To Pairwise'])->toBe(2);
});

test('history contains Remove Vote To Pairwise on vote removal', function (): void {
    $this->election->parseVotes('A>B>C');

    $vote = $this->election->addVote('B>C>A');
    $this->election->removeVote($vote);

    $roles = array_column($this->election->getTimerManager()->getHistory(), 'role');
    expect($roles)->toContain('Remove Vote To Pairwise');
});

test('GetResult appears in history on every call, as the outermost entry', function (): void {
    $this->election->parseVotes('A>B>C');

    // First call: GetResult is the outermost chrono. Inside, Result::__construct()
    // calls getCondorcetWinner() and getCondorcetLoser(), which fire their own
    // inner chronos — all three are recorded in history.
    $this->election->getResult('Schulze');
    $rolesAfterFirst = array_column($this->election->getTimerManager()->getHistory(), 'role');
    expect($rolesAfterFirst)->toContain('GetResult for Schulze');
    // Being outermost, it is the last entry added in this batch.
    expect(end($rolesAfterFirst))->toBe('GetResult for Schulze');

    // Second call: result is cached — only the getResult chrono fires (no inner work).
    $countAfterFirst = \count($this->election->getTimerManager()->getHistory());
    $this->election->getResult('Schulze');
    expect($this->election->getTimerManager()->getHistory())->toHaveCount($countAfterFirst + 1);
});

test('history contains GetWinner entry for CondorcetBasic', function (): void {
    $this->election->parseVotes("A>B>C * 3\nB>C>A * 2");
    $this->election->getCondorcetWinner();

    $roles = array_column($this->election->getTimerManager()->getHistory(), 'role');
    expect($roles)->toContain('GetWinner for CondorcetBasic');
});

test('history contains GetLoser entry for CondorcetBasic', function (): void {
    $this->election->parseVotes("A>B>C * 3\nB>C>A * 2");
    $this->election->getCondorcetLoser();

    $roles = array_column($this->election->getTimerManager()->getHistory(), 'role');
    expect($roles)->toContain('GetLoser for CondorcetBasic');
});

test('history contains GetResult with filter entry when using tag filter', function (): void {
    $this->election->parseVotes('tag1 || A>B>C * 3');
    $this->election->getResult('Schulze', ['tags' => 'tag1', 'withTag' => true]);

    $roles = array_column($this->election->getTimerManager()->getHistory(), 'role');
    expect($roles)->toContain('GetResult with filter');
});

test('sequential (non-nested) chronos respect strict chronological order', function (): void {
    // Add votes after first setStateToVote trigger: each addVote fires an
    // Add Vote To Pairwise chrono with no nesting → fully sequential.
    $this->election->parseVotes('A>B>C'); // triggers doPairwise
    $this->election->addVote('B>C>A');   // sequential add
    $this->election->addVote('C>A>B');   // sequential add

    $history = $this->election->getTimerManager()->getHistory();

    // Collect only the sequential Add Vote entries (indices 1 and 2).
    $addVoteEntries = array_values(array_filter(
        $history,
        static fn(array $e): bool => $e['role'] === 'Add Vote To Pairwise'
    ));

    expect($addVoteEntries)->toHaveCount(2);
    expect($addVoteEntries[0]['timer_end'])->toBeLessThanOrEqual($addVoteEntries[1]['timer_start']);
});
