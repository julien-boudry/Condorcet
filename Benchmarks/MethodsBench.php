<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Algo\Methods\RankedPairs\RankedPairs_Core;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;
use PhpBench\Attributes as Bench;
ini_set('memory_limit','51200M');

class MethodsBench
{
    public array $numberOfCandidates = [3,5,7,10,20,30,40,50,60,70,80,90,100];

    protected Election $election;

    public function __construct ()
    {
        RankedPairs_Core::$MaxCandidates = null;
    }


    protected function buildElection (int $numberOfCandidates, int $numberOfVotes): void
    {
        $this->election = $election = new Election;
        $this->election->setNumberOfSeats((int) ($numberOfCandidates / 3));

        $candidates = [];

        for ($i=0 ; $i < $numberOfCandidates ; $i++) :
            $candidates[] = $election->addCandidate();
        endfor;

        for ($i = 0 ; $i < $numberOfVotes ; $i++) :
            $oneVote = $candidates;
            shuffle($oneVote);

            $election->addVote($oneVote);
        endfor;
    }

    public function provideMethods (): \Generator
    {
        foreach (Condorcet::getAuthMethods() as $method) :
            yield $method => ['method' => $method];
        endforeach;
    }

    public function provideNumberOfCandidates (): \Generator
    {
        foreach ($this->numberOfCandidates as $n) :
            yield $n => ['numberOfCandidates' => $n];
        endforeach;
    }

    public function setUp (array $params): void
    {
        $this->buildElection($params['numberOfCandidates'], 1_000);
    }

    #[Bench\OutputTimeUnit('seconds')]
    #[Bench\ParamProviders(['provideMethods', 'provideNumberOfCandidates'])]
    #[Bench\BeforeMethods('setUp')]
    #[Bench\Warmup(0)]
    #[Bench\Iterations(1)]
    #[Bench\Revs(1)]
    public function benchByCandidates (array $params): void
    {
        try {
            $result = $this->election->getResult($params['method']);
        } catch (CandidatesMaxNumberReachedException $e) {}
    }
}