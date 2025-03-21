<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Converters;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionParameter, FunctionReturn, PublicAPI};
use CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterExport;

class CivsFormat implements ConverterExport
{
    ////// # Static Export Method //////
/**
 * Create a CondorcetElectionFormat file from an Election object.
 * 
 * @api 
 * @return mixed If the file is not provided, it's return a CondorcetElectionFormat as string, else returning null and working directly on the file object (necessary for very large non-aggregated elections, at the risk of memory saturation).
 * @param $election Election with data.
 * @param $file If provided, the function will return null and the result will be writing directly to the file instead. _Note that the file cursor is not rewinding_.
 */
    public static function createFromElection(
        #[FunctionParameter('Election with data')]
        Election $election,
        #[FunctionParameter('If provided, the function will return null and the result will be writing directly to the file instead. _Note that the file cursor is not rewinding_')]
        ?\SplFileObject $file = null
    ): true|string {
        $r = '';

        $header = '# Candidates: ' . implode(' / ', $election->getCandidatesListAsString());

        if ($file) {
            $file->fwrite($header);
        } else {
            $r .= $header;
        }

        $rankedModel = [];

        foreach (array_keys($election->getCandidatesList()) as $candidateId) {
            $rankedModel[$candidateId] = null;
        }

        foreach ($election->getVotesListGenerator() as $vote) {
            $voteRanking = $vote->getContextualRanking($election);

            $ranked = $rankedModel;

            foreach ($voteRanking as $rank => $candidates) {
                foreach ($candidates as $oneCandidate) {
                    $ranked[$election->getCandidateKey($oneCandidate)] = $rank;
                }
            }

            ksort($ranked, \SORT_NUMERIC);

            for ($weightDone = 0; $weightDone < $vote->getWeight($election); $weightDone++) {
                $line = "\n";
                $i = 0;

                foreach ($ranked as $rank) {
                    $i++ > 0 && $line .= ',';
                    $line .= $rank ?? '-';
                }

                if ($file) {
                    $file->fwrite($line);
                } else {
                    $r .= $line;
                }

                $line = '';
            }


        }

        return ($file) ? true : $r;
    }
}
