<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Converters;

use CondorcetPHP\Condorcet\{Candidate, Election};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Tools\Converters\Interface\ConverterImport;

class DavidHillFormat implements ConverterImport
{
    protected array $lines;

    /**
     * @api
     */
    public private(set) readonly array $candidates;

    /**
     * @api
     */
    public private(set) readonly int $NumberOfSeats;
/**
 * Read a Tideman format file
 * @api
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

        (end($this->lines) === '') ? array_pop($this->lines) : null; # Remove bad format from most popular source for this format (elections A01 and A04)

        $this->readNumberOfSeats();
        $this->readCandidatesNames();
        $this->readVotes();
    }
/**
 * Add the data to an election object
 * @api
 * @return mixed The election object
 * @see Tools\Converters\CEF\CondorcetElectionFormat::setDataToAnElection, Tools\Converters\DebianFormat::setDataToAnElection
 * @param $election Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.
 */
#[\Override]
    public function setDataToAnElection(

        ?Election $election = null
    ): Election {
        if ($election === null) {
            $election = new Election;
        }

        $election->setNumberOfSeats($this->NumberOfSeats);

        foreach ($this->candidates as $oneCandidate) {
            $election->addCandidate($oneCandidate);
        }

        foreach ($this->lines as $oneVote) {
            $election->addVote($oneVote);
        }

        return $election;
    }

    // Internal
    protected function readNumberOfSeats(): void
    {
        $first_line = reset($this->lines);

        $this->NumberOfSeats = (int) explode(' ', $first_line)[1];
    }

    protected function readCandidatesNames(): void
    {
        $last_line = end($this->lines);
        $last_line = mb_ltrim($last_line, '"');
        $last_line = explode('" "', $last_line);

        // Remove Election Name
        array_pop($last_line);

        foreach ($last_line as &$oneCandidate) {
            $oneCandidate = str_replace('"', '', $oneCandidate);
            $oneCandidate = new Candidate($oneCandidate);
        }

        $this->candidates = $last_line;
    }

    protected function readVotes(): void
    {
        // Remove last two lines
        array_pop($this->lines); // Last
        array_pop($this->lines); // New Last

        // Remove first line
        array_shift($this->lines);

        // Read each line
        foreach ($this->lines as &$oneVote) {
            $oneVote = explode(' ', $oneVote);

            // Remove first line
            array_shift($oneVote);
            // Remove Last line
            array_pop($oneVote);

            foreach ($oneVote as &$oneRank) {
                $oneRank = (int) $oneRank;
                $oneRank = $this->candidates[$oneRank - 1];
            }
        }
    }
}
