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
use CondorcetPHP\Condorcet\{Candidate, CondorcetUtil, Election};
use CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException;

class CondorcetElectionFormat implements ConverterInterface
{
    ////// # Static Export Method //////

    public const SPECIAL_KEYWORD_EMPTY_RANKING = '/EMPTY_RANKING/';

    #[PublicAPI]
    #[Description("Create a CondorcetElectionFormat file from an Election object.\n")]
    #[FunctionReturn("If the file is not provided, it's return a CondorcetElectionFormat as string, else returning null and working directly on the file object (necessary for very large non-aggregated elections, at the risk of memory saturation).")]
    public static function exportElectionToCondorcetElectionFormat(
        #[FunctionParameter('Election with data')]
        Election $election,
        #[FunctionParameter('If true, will try to reduce number of lines, with quantifier for identical votes')]
        bool $aggregateVotes = true,
        #[FunctionParameter('Add the Number Of Seats parameters to the output')]
        bool $includeNumberOfSeats = true,
        #[FunctionParameter('Add the vote tags information if any. Don\'t work if $aggregateVotes is true')]
        bool $includeTags = true,
        #[FunctionParameter('Non-election candidates will be ignored. If the implicit ranking parameter of the election object is true, the last rank will also be provided to facilitate the reading.')]
        bool $inContext = false,
        #[FunctionParameter('If provided, the function will return null and the result will be writing directly to the file instead. _Note that the file cursor is not rewinding_')]
        ?\SplFileObject $file = null
    ): ?string {
        $r = '';
        $r .= '#/Candidates: ' . implode(' ; ', $election->getCandidatesListAsString())         . "\n";
        $r .= ($includeNumberOfSeats) ? '#/Number of Seats: ' . $election->getNumberOfSeats()   . "\n" : null;
        $r .= '#/Implicit Ranking: ' . ($election->getImplicitRankingRule() ? 'true' : 'false') . "\n";
        $r .= '#/Weight Allowed: ' . ($election->isVoteWeightAllowed() ? 'true' : 'false')      . "\n";
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
                $line .= ($includeTags && !empty($vote->getTags())) ? $vote->getTagsAsString().' || ' : '';

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


    ////// # Reader Object //////

    # Reader Object

    // Const
    protected const CANDIDATES_PATTERN  = '/^#\/Candidates:(?<candidates>.+)$/mi';
    protected const SEATS_PATTERN       = '/^#\/Number of Seats: *(?<seats>[0-9]+) *$/mi';
    protected const IMPLICIT_PATTERN    = '/^#\/Implicit Ranking: *(?<implicitRanking>(true|false)) *$/mi';
    protected const WEIGHT_PATTERN      = '/^#\/Weight Allowed: *(?<weight>(true|false)) *$/mi';


    // Properties
    protected \SplFileObject $file;

    #[PublicAPI]
    public readonly array $candidates;
    #[PublicAPI]
    public readonly int $numberOfSeats;
    #[PublicAPI]
    public readonly bool $implicitRanking;
    #[PublicAPI]
    public readonly bool $voteWeight;

    #[PublicAPI]
    public readonly bool $CandidatesParsedFromVotes;
    #[PublicAPI]
    public readonly int $invalidBlocksCount;

    // Read

    #[PublicAPI]
    #[Description('Read a Condorcet format file, usually using .cvotes file extension')]
    public function __construct(
        #[FunctionParameter('String, valid path to a text file or an object SplFileInfo or extending it like SplFileObject')]
        \SplFileInfo|string $input
    ) {
        $input = ($input instanceof \SplFileInfo) ? $input : new \SplFileInfo($input);

        if ($input instanceof \SplFileObject) {
            $this->file = $input;
        } elseif ($input->isFile() && $input->isReadable()) {
            $this->file = $input->openFile('r');
        } else {
            throw new FileDoesNotExistException('Specified input file does not exist. path: '.$input);
        }

        unset($input); // Memory Optimization

        $this->file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $this->readParameters();

        // Parse candidate directly from votes
        if (empty($this->candidates)) {
            $this->parseCandidatesFromVotes();
            $this->CandidatesParsedFromVotes = true;
        } else {
            $this->CandidatesParsedFromVotes = false;
        }
    }

    #[PublicAPI]
    #[Description('Add the data to an election object')]
    #[FunctionReturn('The election object')]
    #[Related("Tools\DavidHillFormat::setDataToAnElection", "Tools\DebianFormat::setDataToAnElection")]
    public function setDataToAnElection(
        #[FunctionParameter('Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.')]
        Election $election = new Election,
        #[FunctionParameter('Callback function to execute after each registered vote.')]
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
        $this->file->rewind();

        while (!$this->file->eof()) {
            $line = $this->file->fgets();
            $matches = [];

            if (!isset($this->candidates) && preg_match(self::CANDIDATES_PATTERN, $line, $matches)) {
                $parse = $matches['candidates'];
                $parse = CondorcetUtil::prepareParse($parse, false);

                foreach ($parse as &$oneCandidate) {
                    $oneCandidate = new Candidate($oneCandidate);
                }

                $this->addCandidates($parse);
            } elseif (!isset($this->numberOfSeats) && preg_match(self::SEATS_PATTERN, $line, $matches)) {
                $this->numberOfSeats = (int) $matches['seats'];
            } elseif (!isset($this->implicitRanking) && preg_match(self::IMPLICIT_PATTERN, $line, $matches)) {
                $parse = mb_strtolower($matches['implicitRanking']);
                $this->implicitRanking = $this->boolParser($parse);
            } elseif (!isset($this->voteWeight) && preg_match(self::WEIGHT_PATTERN, $line, $matches)) {
                $parse = mb_strtolower($matches['weight']);
                $this->voteWeight = $this->boolParser($parse);
            } elseif (!empty($line) && !str_starts_with($line, '#')) {
                break;
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
                    $oneCandidate = trim($oneCandidate);

                    if ($oneCandidate !== self::SPECIAL_KEYWORD_EMPTY_RANKING) {
                        $candidates[$oneCandidate] = null;
                    }
                }
            }
        }

        $this->addCandidates(array_keys($candidates));
    }

    protected function boolParser(string $parse): bool
    {
        return match ($parse) {
            'true' => true,
                'false' => false,
        };
    }
}
