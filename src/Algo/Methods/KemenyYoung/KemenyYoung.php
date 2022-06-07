<?php
/*
    Part of KEMENY–YOUNG method Module - From the original Condorcet PHP

    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo\Methods\KemenyYoung;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Algo\{Method, MethodInterface, Pairwise};
use CondorcetPHP\Condorcet\Algo\Tools\Permutations;
use SplFixedArray;

// Kemeny-Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung extends Method implements MethodInterface
{
    // Method Name
    public const METHOD_NAME = ['Kemeny–Young','Kemeny-Young','Kemeny Young','KemenyYoung','Kemeny rule','VoteFair popularity ranking','Maximum Likelihood Method','Median Relation'];

    // Method Name
    final public const CONFLICT_WARNING_CODE = 42;

    // Limits
    # If you need 9 candidates, you must use \ini_set('memory_limit','1024M'); before. Do not try to go to 10, it is not viable!
    public static ?int $MaxCandidates = 9;

    // Cache file
    public static bool $devWriteCache = false;
    public static string $cachePath = __DIR__ . '/KemenyYoung-Data/';

    // Kemeny Young Object
    protected ?\SplFileObject $_file;

    // Cache process
    protected readonly int $countElectionCandidates;
    protected readonly array $candidatesKey;
    protected readonly array $candidateKeyMapping;
    protected readonly int $countPossibleRanking;

    // processing
    protected int $MaxScore = -1;
    protected int $Conflits = 0;
    protected int $bestRankingKey;


/////////// PUBLIC ///////////


    // Get the Kemeny ranking
    public function getResult (): Result
    {
        // Cache
        if ( $this->_Result === null ) :
            $this->countElectionCandidates = $this->getElection()->countCandidates();
            $this->candidatesKey = \array_keys($this->getElection()->getCandidatesList());
            $this->candidateKeyMapping = \range(0, $this->countElectionCandidates - 1);
            $this->countPossibleRanking = Permutations::getPossibleCountOfPermutations($this->countElectionCandidates);

            $this->prepareFileCache();
            $this->computeMaxAndConflicts();
            $this->makeRanking();
            $this->conflictInfos();
            $this->_file = null;
        endif;

        // Return
        return $this->_Result;
    }


    protected function getStats (): array
    {
        $election = $this->getElection();
        $explicit = [];

        foreach ($this->getPossibleRankingIterator() as $key => $value) :
            // Human readable
            $i = 1;
            foreach ($value as $candidate_key) :
                $explicit[$key][$i++] = $election->getCandidateObjectFromKey($candidate_key)->getName();
            endforeach;

            $explicit[$key]['score'] = $this->computeOneScore($value, $election->getPairwise());
        endforeach;

        $stats = [];
        $stats['bestScore'] = $this->MaxScore;
        $stats['rankingScore'] = $explicit;

        return $stats;
    }

        protected function conflictInfos (): void
        {
            if ($this->Conflits > 0)  :
                $this->_Result->addWarning(
                    type: self::CONFLICT_WARNING_CODE,
                    msg: ($this->Conflits + 1).';'.$this->MaxScore
                );
            endif;
        }


/////////// COMPUTE ///////////


    //:: Kemeny-Young ALGORITHM. :://

    protected function prepareFileCache (): void
    {
        /** @infection-ignore-all */
        $path = self::$cachePath.$this->countElectionCandidates.'.data';
        $f = new \SplFileInfo($path);

        // Create cache file if not exist, or temp cache file if candidates count > 9
        if (self::$devWriteCache || !$f->isFile()) :
            if (!self::$devWriteCache && !$f->isFile() && $this->countElectionCandidates > 9) :
                $this->_file = new \SplTempFileObject();
            else :
                $this->_file = new \SplFileObject($f->getPathname(), 'w+');
            endif;

            (new Permutations ($this->countElectionCandidates))->writeResults($this->_file);
        else :
            if (!($f instanceof \SplFileObject)) :
                $this->_file = $f->openFile('r');
            else:
                $this->_file = $f;
            endif;
        endif;
    }

    protected function getPossibleRankingIterator (): \Generator
    {
        $this->_file->rewind();

        while (!$this->_file->eof()) :
            $key = $this->_file->key();

            $l = trim($this->_file->fgets());
            if (\strlen($l) < 1) : continue; endif;

            $onePossibleRanking = $this->convertLineToRanking($l);

            yield $key => $onePossibleRanking;
        endwhile;
    }

    protected function convertLineToRanking (string $line): SplFixedArray
    {
        $oneResult = explode(',', $line);

        foreach ($oneResult as &$oneCandidateId) :
            $oneCandidateId = $this->candidatesKey[(int) $oneCandidateId];
        endforeach;

        $onePossibleRanking = new SplFixedArray($this->countElectionCandidates);
        $rank = 0;
        foreach($oneResult as $oneCandidate) :
            $onePossibleRanking[$rank++] = (int) $oneCandidate;
        endforeach;

        return $onePossibleRanking;
    }

    protected function computeMaxAndConflicts (): void
    {
        $pairwise = $this->getElection()->getPairwise();

        foreach ($this->getPossibleRankingIterator() as $keyScore => $onePossibleRanking) :
            $rankingScore = $this->computeOneScore($onePossibleRanking, $pairwise);

            // Max Ranking Score
            if ($rankingScore > $this->MaxScore) :
                $this->MaxScore = $rankingScore;
                $this->Conflits = 0;
                $this->bestRankingKey = $keyScore;
            elseif ($rankingScore === $this->MaxScore) :
                $this->Conflits++;
            endif;
        endforeach;
    }

    protected function computeOneScore (SplFixedArray $ranking, Pairwise $pairwise): int
    {
        $rankingScore = 0;
        $do = [];

        foreach ($ranking as $candidateId) :
            $do[] = $candidateId;

            foreach ($ranking as $rankCandidate) :
                if (!\in_array(needle: $rankCandidate, haystack: $do, strict: true)) :
                    $rankingScore += $pairwise[$candidateId]['win'][$rankCandidate];
                endif;
            endforeach;
        endforeach;

        return $rankingScore;
    }


    /*
    I do not know how in the very unlikely event that several possible classifications have the same highest score.
    In the current state, one of them is chosen arbitrarily.

    See issue on Github : https://github.com/julien-boudry/Condorcet/issues/6
    */
    protected function makeRanking (): void
    {
        $this->_file->seek($this->bestRankingKey);

        $winnerRanking = $this->convertLineToRanking($this->_file->fgets());

        $winnerRanking = [null, ...$winnerRanking->toArray()];
        unset($winnerRanking[0]);

        $this->_Result = $this->createResult($winnerRanking);

        $this->_file = null;
    }
}
