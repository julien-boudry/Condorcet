<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Converters;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\{Candidate, Election, Vote};

class DebianFormat implements ConverterInterface
{
    protected array $lines;

    #[PublicAPI]
    public readonly array $candidates;
    #[PublicAPI]
    public readonly array $votes;

    #[PublicAPI]
    #[Description("Read a Tideman format file")]
    public function __construct(
        #[FunctionParameter('File absolute path')]
        string $filePath
    ) {
        $this->lines = \file($filePath, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);

        $this->readCandidatesNames();
        $this->readVotes();
    }

    #[PublicAPI]
    #[Description("Add the Debian data to an election object")]
    #[FunctionReturn("The election object")]
    #[Related("Tools\CondorcetElectionFormat::setDataToAnElection", "Tools\DavidHillFormat::setDataToAnElection")]
    public function setDataToAnElection(
        #[FunctionParameter('Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.')]
        ?Election $election = null
    ): Election {
        if ($election === null) {
            $election = new Election;
            $election->setNumberOfSeats(1);
        }

        foreach ($this->candidates as $oneCandidate) {
            $election->addCandidate($oneCandidate);
        }

        foreach ($this->votes as $oneVote) {
            $election->addVote($oneVote);
        }

        return $election;
    }

    // Internal

    protected function readCandidatesNames(): void
    {
        $pattern = '/^.*Option [1-9].*: (?<candidateName>.*)$/m';

        $candidatesLines = preg_grep($pattern, $this->lines);

        $candidates = [];
        // $k = 1;

        foreach ($candidatesLines as $oneCandidateLine) {
            $match = null;
            \preg_match($pattern, $oneCandidateLine, $match);

            // $candidates[$k++] = new Candidate( \trim($match['candidateName']) );
            // $candidates[] = new Candidate( \trim( $match['candidateName']) );
            $candidateName = $match['candidateName'];
            \mb_check_encoding($candidateName) || ($candidateName = \mb_convert_encoding($candidateName, 'UTF-8', 'ISO-8859-16'));
            $candidateName = \trim($candidateName);

            $candidates[] = new Candidate($candidateName);
        }

        $this->candidates = $candidates;
    }

    protected function readVotes(): void
    {
        $pattern = '/^(V:)? ?(?<Ranking>[1-9-]+)[ \t]+(?<MD5>[a-z0-9]+)$/m';

        $votesLines = preg_grep($pattern, $this->lines);
        $votes = [];

        foreach ($votesLines as $oneVoteLine) {
            $match = null;

            \preg_match($pattern, trim($oneVoteLine), $match);
            $oneVoteLineRanking = str_split($match['Ranking']);

            $oneVote = [];
            foreach ($oneVoteLineRanking as $candidateKey => $candidateRankEvaluation) {
                if (is_numeric($candidateRankEvaluation)) {
                    $oneVote[(int) $candidateRankEvaluation][] = $this->candidates[$candidateKey];
                }
            }

            $votes[] = new Vote($oneVote, $match['MD5']);
        }

        $this->votes = $votes;
    }
}
