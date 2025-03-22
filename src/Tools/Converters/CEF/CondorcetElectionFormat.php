<?php

/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Converters\CEF;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException;
use CondorcetPHP\Condorcet\Tools\Converters\Interface\{ConverterExport, ConverterImport};
use SplTempFileObject;

class CondorcetElectionFormat implements ConverterExport, ConverterImport
{
    ////// # Static Export Method //////

    public const string SPECIAL_KEYWORD_EMPTY_RANKING = '/EMPTY_RANKING/';
/**
 * Create a CondorcetElectionFormat file from an Election object.
 * 
 * @api 
 * @return mixed If the file is not provided, it's return a CondorcetElectionFormat as string, else returning null and working directly on the file object (necessary for very large non-aggregated elections, at the risk of memory saturation).
 * @param $election Election with data.
 * @param $aggregateVotes If true, will try to reduce number of lines, with quantifier for identical votes.
 * @param $includeNumberOfSeats Add the Number Of Seats parameters to the output.
 * @param $includeTags Add the vote tags information if any. Not working if $aggregateVotes is true.
 * @param $inContext Non-election candidates will be ignored. If the implicit ranking parameter of the election object is true, the last rank will also be provided to facilitate the reading.
 * @param $file If provided, the function will return null and the result will be writing directly to the file instead. _Note that the file cursor is not rewinding_.
 */
    public static function createFromElection(

        Election $election,

        bool $aggregateVotes = true,

        bool $includeNumberOfSeats = true,

        bool $includeTags = true,

        bool $inContext = false,

        ?\SplFileObject $file = null
    ): ?string {
        $r = '';
        $r .= '#/Candidates: ' . implode(' ; ', $election->getCandidatesListAsString()) . "\n";
        $r .= ($includeNumberOfSeats) ? '#/Number of Seats: ' . $election->getNumberOfSeats() . "\n" : null;
        $r .= '#/Implicit Ranking: ' . ($election->getImplicitRankingRule() ? 'true' : 'false') . "\n";
        $r .= '#/Weight Allowed: ' . ($election->isVoteWeightAllowed() ? 'true' : 'false') . "\n";
        // $r .= "\n";

        if ($file) {
            $file->fwrite($r);
            $r = '';
        }

        if ($aggregateVotes) {
            $r .= "\n" . $election->getVotesListAsString($inContext);
            if ($file) {
                $file->fwrite($r);
            }
        } else {
            foreach ($election->getVotesListGenerator() as $vote) {
                $line = "\n";
                $line .= ($includeTags && !empty($vote->tags)) ? $vote->getTagsAsString() . ' || ' : '';

                $voteString = $vote->getSimpleRanking($inContext ? $election : null);
                $line .= !empty($voteString) ? $voteString : self::SPECIAL_KEYWORD_EMPTY_RANKING;

                if ($file) {
                    $file->fwrite($line);
                } else {
                    $r .= $line;
                }
            }
        }

        return ($file) ? null : $r;
    }
/**
 * Create a CondorcetElectionFormat object from string.
 * 
 * @api 
 * @param $input CondorcetElectionFormat string to parse.
 */
    public static function createFromString(

        string $input
    ): self
    {
        $file = new SplTempFileObject;
        $file->fwrite($input);

        return new self($file);
    }

    public static function boolParser(string $parse): bool
    {
        return match (mb_strtolower($parse)) {
            'true' => true,
            'false' => false,
            default => true
        };
    }


    ////// # Reader Object //////

    // Properties
    protected \SplFileObject $file;

    /**
     * @api
     */
    public private(set) readonly array $parameters;

    /**
     * @api
     */
    public private(set) readonly array $candidates;

    /**
     * @api
     */
    public private(set) readonly int $numberOfSeats;

    /**
     * @api
     */
    public private(set) readonly bool $implicitRanking;

    /**
     * @api
     */
    public private(set) readonly bool $voteWeight;

    /**
     * @api
     */
    public private(set) readonly bool $CandidatesParsedFromVotes;

    /**
     * @api
     */
    public private(set) readonly int $invalidBlocksCount;

    // Read
/**
 * Read a Condorcet format file, usually using .cvotes file extension
 * @api 
 * @param $input String, valid path to a text file or an object SplFileInfo or extending it like SplFileObject.
 */
    public function __construct(

        \SplFileInfo|string $input
    ) {
        $input = ($input instanceof \SplFileInfo) ? $input : new \SplFileInfo($input);

        if ($input instanceof \SplFileObject) {
            $this->file = $input;
        } elseif ($input->isFile() && $input->isReadable()) {
            $this->file = $input->openFile('r');
        } else {
            throw new FileDoesNotExistException('Specified input file does not exist. path: ' . $input);
        }

        unset($input); // Memory Optimization

        $this->file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $this->readParameters();
        $this->interpretStandardParameters();


        // Parse candidate directly from votes
        if (empty($this->candidates)) {
            $this->parseCandidatesFromVotes();
            $this->CandidatesParsedFromVotes = true;
        } else {
            $this->CandidatesParsedFromVotes = false;
        }
    }
/**
 * Add the data to an election object
 * @api 
 * @return mixed The election object
 * @see Tools\DavidHillFormat::setDataToAnElection, Tools\DebianFormat::setDataToAnElection
 * @param $election Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.
 * @param $callBack Callback function to execute after each registered vote.
 */
#[\Override]
    public function setDataToAnElection(

        Election $election = new Election,

        ?\Closure $callBack = null
    ): Election {
        // Parameters
        // Set number of seats if specified in file
        ($this->numberOfSeats ?? false) && $election->setNumberOfSeats($this->numberOfSeats);

        // Set explicit pairwise mode if specified in file
        $this->implicitRanking ??= $election->getImplicitRankingRule();
        $election->setImplicitRanking($this->implicitRanking);

        // Set Vote weight (Condorcet disable it by default)
        $this->voteWeight ??= $election->isVoteWeightAllowed();
        $election->allowsVoteWeight($this->voteWeight);


        // Candidates
        foreach ($this->candidates as $oneCandidate) {
            $election->addCandidate($oneCandidate);
        }

        // Votes
        $this->file->rewind();
        $this->invalidBlocksCount = $election->parseVotesWithoutFail(input: $this->file, callBack: $callBack);

        return $election;
    }

    // Internal

    protected function addCandidates(array $candidates): void
    {
        sort($candidates, \SORT_NATURAL);
        $this->candidates = $candidates;
    }

    protected function readParameters(): void
    {
        $parameters = [];

        $this->file->rewind();

        while (!$this->file->eof()) {
            $line = $this->file->fgets();
            $matches = [];
            $parameterMatch = [];

            if (preg_match('/^#\/(?<parameter_name>.+):(?<parameter_value>.+)$/mi', $line, $parameterMatch)) {
                $parameters[mb_trim($parameterMatch['parameter_name'])] = mb_trim($parameterMatch['parameter_value']);
                continue;
            }

            if (!empty($line) && !str_starts_with($line, '#')) {
                break;
            }
        }

        $this->parameters = $parameters;
    }

    protected function interpretStandardParameters(): void
    {
        foreach ($this->parameters as $parameterName => $parameterValue) {
            if ($parameter = StandardParameter::tryFrom(mb_strtolower($parameterName))) {

                $parameterValue = $parameter->formatValue($parameterValue);

                if (!isset($this->candidates) && $parameter === StandardParameter::CANDIDATES) {
                    $this->addCandidates($parameterValue);
                } elseif (!isset($this->numberOfSeats) && $parameter === StandardParameter::SEATS) {
                    $this->numberOfSeats = $parameterValue;
                } elseif (!isset($this->implicitRanking) && $parameter === StandardParameter::IMPLICIT) {
                    $this->implicitRanking = $parameterValue;
                } elseif (!isset($this->voteWeight) && $parameter === StandardParameter::WEIGHT) {
                    $this->voteWeight = $parameterValue;
                }
            }
        }
    }

    protected function parseCandidatesFromVotes(): void
    {
        $this->file->rewind();

        $candidates = [];

        while (!$this->file->eof()) {
            $line = $this->file->fgets();

            if (!empty($line) && !str_starts_with($line, '#')) {
                if (($pos = mb_strpos($line, '||')) !== false) {
                    $line = mb_substr($line, ($pos + 2));
                }

                if (($pos = mb_strpos($line, '||')) !== false) {
                    $line = mb_substr($line, ($pos + 2));
                }

                foreach (['#', '*', '^'] as $c) {
                    if (($pos = mb_strpos($line, $c)) !== false) {
                        $line = mb_substr($line, 0, $pos);
                    }
                }

                $line = str_replace('>', '=', $line);
                $line = explode('=', $line);

                foreach ($line as $oneCandidate) {
                    $oneCandidate = mb_trim($oneCandidate);

                    if ($oneCandidate !== self::SPECIAL_KEYWORD_EMPTY_RANKING) {
                        $candidates[$oneCandidate] = null;
                    }
                }
            }
        }

        $this->addCandidates(array_keys($candidates));
    }

}
