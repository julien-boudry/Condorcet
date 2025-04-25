<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo\Methods;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Stats\{EmptyStats, StatsInterface};
use CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException;
use Override;

/**
 * Condorcet Basic Class, provide natural Condorcet winner or looser
 * @internal
 */
class CondorcetBasic extends Method implements MethodInterface
{
    // Method Name
    public const array METHOD_NAME = ['CondorcetBasic'];

    // Basic Condorcet
    protected ?int $CondorcetWinner = null;
    protected ?int $CondorcetLoser = null;


    /////////// PUBLIC ///////////


    #[Override]
    public function getResult(): never
    {
        throw new AlgorithmWithoutRankingFeatureException(self::METHOD_NAME[0]);
    }


    protected function getStats(): StatsInterface
    {
        return new EmptyStats;
    }


    // Get a Condorcet certified winner. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
    public function getWinner(): ?int
    {
        // Cache
        if ($this->CondorcetWinner !== null) {
            return $this->CondorcetWinner;
        }

        // -------

        // Basic Condorcet calculation
        $candidates = array_keys($this->getElection()->candidates);
        $pairwise = $this->getElection()->getPairwise();

        foreach ($candidates as $candidateKey) {
            $winner = true;

            foreach ($candidates as $challengerKey) {
                if ($candidateKey === $challengerKey) {
                    continue;
                }

                if (!$pairwise->candidateKeyWinVersus($candidateKey, $challengerKey)) {
                    $winner = false;
                    break;
                }
            }

            if ($winner) {
                $this->CondorcetWinner = $candidateKey;
                return $this->CondorcetWinner;
            }
        }

        return null;
    }

    // Get a Condorcet certified loser. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
    public function getLoser(): ?int
    {
        // Cache
        if ($this->CondorcetLoser !== null) {
            return $this->CondorcetLoser;
        }

        // -------

        // Basic Condorcet calculation
        // Basic Condorcet calculation
        $candidates = array_keys($this->getElection()->candidates);
        $pairwise = $this->getElection()->getPairwise();

        foreach ($candidates as $candidateKey) {
            $loser = true;

            foreach ($candidates as $challengerKey) {
                if ($candidateKey === $challengerKey) {
                    continue;
                }

                if ($pairwise->compareCandidatesKeys($candidateKey, $challengerKey) >= 0) {
                    $loser = false;
                    break;
                }
            }

            if ($loser) {
                $this->CondorcetLoser = $candidateKey;
                return $this->CondorcetLoser;
            }
        }

        return null;
    }
}
