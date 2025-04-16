<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/



/////////// TOOLS FOR MODULAR ALGORITHMS ///////////

namespace CondorcetPHP\Condorcet\Algo\Tools;

use CondorcetPHP\Condorcet\Election;

// Generic for Algorithms
/**
 * @internal
 */
abstract class TieBreakersCollection
{
    public static function electSomeLosersbasedOnPairwiseComparaison(Election $election, array $candidatesKeys): array
    {
        $pairwise = $election->getPairwise();
        $pairwiseStats = PairwiseStats::PairwiseComparison($pairwise);
        $tooKeep = [];

        foreach ($candidatesKeys as $oneCandidateKeyTotest) {
            $select = true;
            foreach ($candidatesKeys as $oneChallengerKey) {
                if ($oneCandidateKeyTotest === $oneChallengerKey) {
                    continue;
                }

                if ($pairwise[$oneCandidateKeyTotest]['win'][$oneChallengerKey] > $pairwise[$oneCandidateKeyTotest]['lose'][$oneChallengerKey] ||
                        $pairwiseStats[$oneCandidateKeyTotest]['balance'] > $pairwiseStats[$oneChallengerKey]['balance'] ||
                        $pairwiseStats[$oneCandidateKeyTotest]['win'] > $pairwiseStats[$oneChallengerKey]['win']
                ) {
                    $select = false;
                }
            }

            if ($select) {
                $tooKeep[] = $oneCandidateKeyTotest;
            }
        }

        return (\count($tooKeep) > 0) ? $tooKeep : $candidatesKeys;
    }

    public static function tieBreakerWithAnotherMethods(Election $election, array $methods, array $candidatesKeys): array
    {
        foreach ($methods as $oneMethod) {
            $tooKeep = [];

            $methodResults = $election->getResult($oneMethod)->rawRanking;

            foreach ($methodResults as $rankValue) {
                foreach ($rankValue as $oneCandidateKey) {
                    if (\in_array($oneCandidateKey, $candidatesKeys, true)) {
                        $tooKeep[] = $oneCandidateKey;
                    }
                }

                if (\count($tooKeep) > 0 && \count($tooKeep) !== \count($candidatesKeys)) {
                    return $tooKeep;
                }
            }
        }

        return $candidatesKeys;
    }
}
