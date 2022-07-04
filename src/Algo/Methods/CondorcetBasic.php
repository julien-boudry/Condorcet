<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods;

use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Throwable\AlgorithmWithoutRankingFeatureException;

// Condorcet Basic Class, provide natural Condorcet winner or looser
class CondorcetBasic extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['CondorcetBasic'];

    // Basic Condorcet
    protected ?int $_CondorcetWinner = null;
    protected ?int $_CondorcetLoser = null;


    /////////// PUBLIC ///////////


    public function getResult(): never
    {
        throw new AlgorithmWithoutRankingFeatureException(self::METHOD_NAME[0]);
    }


    protected function getStats(): array
    {
        return [];
    }


    // Get a Condorcet certified winner. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
    public function getWinner(): ?int
    {
        // Cache
        if ($this->_CondorcetWinner !== null) {
            return $this->_CondorcetWinner;
        }

        //////

        // Basic Condorcet calculation
        foreach ($this->getElection()->getPairwise() as $candidate_key => $candidat_detail) {
            $winner = true;

            foreach ($candidat_detail['win'] as $challenger_key => $win_count) {
                if ($win_count <= $candidat_detail['lose'][$challenger_key]) {
                    $winner = false;
                    break;
                }
            }

            if ($winner) {
                $this->_CondorcetWinner = $candidate_key;
                return $this->_CondorcetWinner;
            }
        }

        return null;
    }

    // Get a Condorcet certified loser. If there is none = null. You can force a winner choice with alternative supported methods ($substitution)
    public function getLoser(): ?int
    {
        // Cache
        if ($this->_CondorcetLoser !== null) {
            return $this->_CondorcetLoser;
        }

        //////

        // Basic Condorcet calculation
        foreach ($this->getElection()->getPairwise() as $candidate_key => $candidat_detail) {
            $loser = true;

            foreach ($candidat_detail['lose'] as $challenger_key => $lose_count) {
                if ($lose_count <= $candidat_detail['win'][$challenger_key]) {
                    $loser = false;
                    break;
                }
            }

            if ($loser) {
                $this->_CondorcetLoser = $candidate_key;
                return $this->_CondorcetLoser;
            }
        }

        return null;
    }
}
