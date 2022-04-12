<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools;

use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Election;

class TidemanDataCollection
{
    protected array $lines;
    protected array $candidates;

    #[PublicAPI]
    #[Description("Read a Tideman format file")]
    public function __construct (
        #[FunctionParameter('File absolute path')]
        string $filePath
    )
    {
        $this->lines = \file($filePath , \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);

        $this->readCandidatesNames();
        $this->readVotes();
    }

    #[PublicAPI]
    #[Description("Add the tideman data ton an election object")]
    #[FunctionReturn("The election object")]
    public function setDataToAnElection (
        #[FunctionParameter('Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.')]
        ?Election $election = null
    ): Election
    {
        if ($election === null) :
            $election = new Election;
        endif;

        foreach ($this->candidates as $oneCandidate) :
            $election->addCandidate($oneCandidate);
        endforeach;

        foreach($this->lines as $oneVote) :
            $election->addVote($oneVote);
        endforeach;

        return $election;
    }

    // Internal
    protected function readCandidatesNames (): void
    {
        $last_line = explode(' ', end($this->lines));

        // Remove Election Name
        array_pop($last_line);

        foreach($last_line as &$oneCandidate) :
            $oneCandidate = str_replace('"', '', $oneCandidate);
            $oneCandidate = new Candidate($oneCandidate);
        endforeach;

        $this->candidates = $last_line;
    }

    protected function readVotes (): void
    {
        // Remove last two lines
        array_pop( $this->lines); // Last
        array_pop( $this->lines); // New Last

        // Remove first line
        array_shift( $this->lines);

        // Read each line
        foreach ($this->lines as &$oneVote) :
            $oneVote = explode(' ', $oneVote);

            // Remove first line
            array_shift($oneVote);
            // Remove Last line
            array_pop($oneVote);

            foreach ($oneVote as &$oneRank) :
                $oneRank = $this->candidates[$oneRank - 1];
            endforeach;
        endforeach;
    }

}
