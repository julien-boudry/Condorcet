<?php
/*
    Part of INSTANT-RUNOFF method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\InstantRunoff;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface};
use CondorcetPHP\Condorcet\Algo\Tools\TieBreakersCollection;

class InstantRunoff extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Instant-runoff', 'InstantRunoff', 'preferential voting', 'ranked-choice voting', 'alternative vote', 'AlternativeVote', 'transferable vote', 'Vote alternatif'];

    protected ?array $_Stats = null;

    protected function getStats(): array
    {
        $stats = [];

        foreach ($this->_Stats as $oneIterationKey => $oneIterationData) :
            if (\count($oneIterationData) === 1) :
                break;
            endif;

            foreach ($oneIterationData as $candidateKey => $candidateValue) :
                $stats[$oneIterationKey][(string) $this->_selfElection->getCandidateObjectFromKey($candidateKey)] = $candidateValue;
            endforeach;
        endforeach;

        return $stats;
    }


/////////// COMPUTE ///////////

    //:: Alternative Vote ALGORITHM. :://

    protected function compute () : void
    {
        $candidateCount = $this->_selfElection->countCandidates();
        $majority = $this->_selfElection->sumValidVotesWeightWithConstraints() / 2;

        $candidateDone = [];
        $result = [];
        $CandidatesWinnerCount = 0;
        $CandidatesLoserCount = 0;

        $iteration = 0;

        while (\count($candidateDone) < $candidateCount) :
            $score = $this->makeScore($candidateDone);
            $maxScore = \max($score);
            $minScore = \min($score);

            $this->_Stats[++$iteration] = $score;

            if ( $maxScore > $majority ) :
                foreach ($score as $candidateKey => $candidateScore) :
                    if ($candidateScore !== $maxScore) :
                        continue;
                    else :
                        $candidateDone[] = $candidateKey;
                        $result[++$CandidatesWinnerCount][] = $candidateKey;
                    endif;
                endforeach;
            else :
                $LosersToRegister = [];

                foreach ($score as $candidateKey => $candidateScore) :
                    if ($candidateScore !== $minScore) :
                        continue;
                    else :
                        $LosersToRegister[] = $candidateKey;
                    endif;
                endforeach;

                // Tie Breaking
                $round = \count($LosersToRegister);
                for ($i = 1 ; $i < $round ; $i++) : // A little silly. But ultimately shorter and simpler.
                    $LosersToRegister = TieBreakersCollection::tieBreaker_1($this->_selfElection ,$LosersToRegister);
                endfor;

                $CandidatesLoserCount += \count($LosersToRegister);
                \array_push($candidateDone, ...$LosersToRegister);
                $result[$candidateCount - $CandidatesLoserCount + 1] = $LosersToRegister;
            endif;

        endwhile;

        $this->_Result = $this->createResult($result);
    }

    protected function makeScore (array $candidateDone) : array
    {
        $score = [];

        foreach ($this->_selfElection->getCandidatesList() as $oneCandidate) :
            if (!\in_array(needle: $this->_selfElection->getCandidateKey($oneCandidate), haystack: $candidateDone, strict: true)) :
                $score[$this->_selfElection->getCandidateKey($oneCandidate)] = 0;
            endif;
        endforeach;

        foreach ($this->_selfElection->getVotesManager()->getVotesValidUnderConstraintGenerator() as $oneVote) :

            $weight = $oneVote->getWeight($this->_selfElection);

            foreach ($oneVote->getContextualRanking($this->_selfElection) as $oneRank) :
                foreach ($oneRank as $oneCandidate) :
                    if (\count($oneRank) !== 1) :
                        break;
                    elseif (!\in_array(needle: ($candidateKey = $this->_selfElection->getCandidateKey($oneCandidate)), haystack: $candidateDone, strict: true)) :
                        $score[$candidateKey] += $weight;
                        break 2;
                    endif;
                endforeach;
            endforeach;
        endforeach;

        return $score;
    }
}
