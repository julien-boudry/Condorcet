<?php declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsCore;
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\{Condorcet, Election};
use CondorcetPHP\Condorcet\Throwable\MethodLimitReachedException;
use CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer;
use PhpBench\Attributes as Bench;

ini_set('memory_limit', '51200M');

class MethodsNonProportionalBench
{
    public bool $IS_A_PROPORTIONAL_BENCH = false;

    public array $numberOfCandidates = [3, 5, 6, 7, 8, 9, 10, 11, 20, 30, 40, 50, 60, 70, 80, 90, 100];

    protected Election $election;

    public function __construct()
    {
        RankedPairsCore::$MaxCandidates = null;
        KemenyYoung::$MaxCandidates = 11;
    }

    protected function buildElection(int $numberOfCandidates, int $numberOfVotes): void
    {
        $this->election = $election = new Election;
        $this->election->setSeatsToElect(max(1, (int) ($numberOfCandidates / 3)));
        $this->election->setStatsVerbosity(StatsVerbosity::STD);

        $candidates = [];

        for ($i = 0; $i < $numberOfCandidates; $i++) {
            $candidates[] = $election->addCandidate();
        }

        $randomizer = new VoteRandomizer($candidates, 'CondorcetReproductibleSeed');

        for ($i = 0; $i < $numberOfVotes; $i++) {
            $oneVote = $randomizer->getNewVote();
            $election->addVote($oneVote);
        }
    }

    public function provideMethods(): \Generator
    {
        foreach (Condorcet::getAuthMethods() as $method) {
            $class = Condorcet::getMethodClass($method);

            if ($class::IS_PROPORTIONAL === $this->IS_A_PROPORTIONAL_BENCH) {
                yield $method => ['method' => $method];
            }
        }
    }

    public function provideNumberOfCandidates(): \Generator
    {
        foreach ($this->numberOfCandidates as $n) {
            yield $n => ['numberOfCandidates' => $n];
        }
    }

    public function setUp(array $params): void
    {
        $this->buildElection($params['numberOfCandidates'], 1_000);
    }

    #[Bench\OutputTimeUnit('seconds')]
    #[Bench\ParamProviders(['provideMethods', 'provideNumberOfCandidates'])]
    #[Bench\BeforeMethods('setUp')]
    #[Bench\Warmup(1)]
    #[Bench\Iterations(3)]
    #[Bench\Revs(1)]
    public function benchByCandidates(array $params): void
    {
        try {
            $result = $this->election->getResult($params['method']);
        } catch (MethodLimitReachedException) {
        }

        $this->election->resetMethodsComputation();
    }
}
