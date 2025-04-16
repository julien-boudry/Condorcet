<?php declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Benchmarks;

use CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung\KemenyYoung;
use CondorcetPHP\Condorcet\Election;
use PhpBench\Attributes as Bench;

ini_set('memory_limit', '51200M');

// Must use --executor=memory_centric_microtime

class KemenyYoungBench
{
    public function __construct()
    {
        KemenyYoung::$MaxCandidates = 10;
    }

    public function provideCandidatesCount(): \Generator
    {
        for ($i = 1; $i <= 10; $i++) {
            yield $i => ['candidatesCount' => $i];
        }
    }


    #[Bench\ParamProviders(['provideCandidatesCount'])]
    #[Bench\OutputTimeUnit('milliseconds')]
    #[Bench\Warmup(1)]
    #[Bench\Iterations(3)]
    #[Bench\Revs(4)]
    public function benchKemenyYoung(array $params): void
    {
        $election = new Election;

        for ($i = 0; $i < $params['candidatesCount']; $i++) {
            $candidates[] = $election->addCandidate();
        }

        $election->addVote($candidates);

        $election->getResult('Kemeny–Young');
    }
}
