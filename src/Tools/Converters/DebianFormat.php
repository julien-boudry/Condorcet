<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Tools\Converters;

use CondorcetPHP\Condorcet\{Candidate, Election, Vote};
use CondorcetPHP\Condorcet\Throwable\ElectionFileFormatParseException;
use CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport;

/**
 * Imports election data from Debian voting format.
 */
class DebianFormat implements ConverterImport
{
    protected array $lines;

    /**
     * @api
     */
    public private(set) readonly array $candidates;

    /**
     * @api
     */
    public private(set) readonly array $votes;

    /**
     * Read a Tideman format file.
     *
     * @api
     *
     * @param $filePath File absolute path.
     */
    public function __construct(
        string $filePath
    ) {
        $lineFile = file($filePath, \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);

        if ($lineFile !== false) {
            $this->lines = $lineFile;
        } else {
            throw new \RuntimeException("Unable to read the file: {$filePath}");
        }

        $this->readCandidatesNames();
        $this->readVotes();
    }

    /**
     * Add the Debian data to an election object.
     *
     * @api
     *
     * @see \CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat::setDataToAnElection()
     * @see \CondorcetPHP\Condorcet\Tools\Converters\DavidHillFormat::setDataToAnElection()
     *
     * @param $election Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.
     *
     * @return Election The election object
     */
    #[\Override]
    public function setDataToAnElection(
        ?Election $election = null
    ): Election {
        if ($election === null) {
            $election = new Election;
            $election->setSeatsToElect(1);
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

        if ($candidatesLines === false) {
            throw new ElectionFileFormatParseException('Unable to read candidates names');
        }

        $candidates = [];

        foreach ($candidatesLines as $oneCandidateLine) {
            $match = null;
            preg_match($pattern, $oneCandidateLine, $match);

            $candidateName = $match['candidateName']; // @phpstan-ignore offsetAccess.notFound
            if (!mb_check_encoding($candidateName)) {
                $candidateName = mb_convert_encoding($candidateName, 'UTF-8', 'ISO-8859-16');
            }
            $candidateName = mb_trim($candidateName);

            $candidates[] = new Candidate($candidateName);
        }

        $this->candidates = $candidates;
    }

    protected function readVotes(): void
    {
        $pattern = '/^(V:)? ?(?<Ranking>[1-9-]+)[ \t]+(?<MD5>[a-z0-9]+)$/m';

        $votesLines = preg_grep($pattern, $this->lines);

        if ($votesLines === false) {
            throw new ElectionFileFormatParseException('Unable to read candidates names');
        }

        $votes = [];

        foreach ($votesLines as $oneVoteLine) {
            $match = null;

            preg_match($pattern, mb_trim($oneVoteLine), $match);
            $oneVoteLineRanking = mb_str_split($match['Ranking']); // @phpstan-ignore offsetAccess.notFound

            $oneVote = [];
            foreach ($oneVoteLineRanking as $candidateKey => $candidateRankEvaluation) {
                if (is_numeric($candidateRankEvaluation)) {
                    $oneVote[(int) $candidateRankEvaluation][] = $this->candidates[$candidateKey];
                }
            }

            $votes[] = new Vote($oneVote, $match['MD5']); // @phpstan-ignore offsetAccess.notFound
        }

        $this->votes = $votes;
    }
}
