<?php declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairsCore;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Tools\Randomizers\VoteRandomizer;
use PhpBench\Attributes as Bench;

ini_set('memory_limit', '51200M');

class PairwiseNumberOfCandidatesBench
{
    public array $numberOfCandidates = [3, 5, 7, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
    public array $numberOfVotes = [10, 100, 1000, 10000];


    protected Election $election;

    public function __construct()
    {
        RankedPairsCore::$MaxCandidates = null;
    }


    protected function buildElection(int $numberOfCandidates, int $numberOfVotes): void
    {
        $this->election = $election = new Election;
        $this->election->setNumberOfSeats((int) ($numberOfCandidates / 3));

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

    public function provideNumberOfCandidates(): \Generator
    {
        foreach ($this->numberOfCandidates as $n) {
            yield $n => ['numberOfCandidates' => $n];
        }
    }

    public function provideNumberOfVotes(): \Generator
    {
        foreach ($this->numberOfVotes as $n) {
            yield $n => ['numberOfVotes' => $n];
        }
    }

    #[Bench\OutputTimeUnit('seconds')]
    #[Bench\ParamProviders(['provideNumberOfCandidates', 'provideNumberOfVotes'])]
    #[Bench\Warmup(0)]
    #[Bench\Iterations(1)]
    #[Bench\Revs(1)]
    public function benchByCandidates(array $params): void
    {
        $this->buildElection($params['numberOfCandidates'], $params['numberOfVotes']);
    }
}
