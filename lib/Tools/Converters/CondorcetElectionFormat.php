<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tools\Converters;

use CondorcetPHP\Condorcet\Candidate;
use CondorcetPHP\Condorcet\CondorcetUtil;
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionParameter, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Throwable\FileDoesNotExistException;

class CondorcetElectionFormat implements ConverterInterface
{

    ////// # Static Export Method //////

    #[PublicAPI]
    #[Description("Create a CondorcetElectionFormat file from an Election object.\n")]
    #[FunctionReturn("If the file is not provided, it's return a CondorcetElectionFormat as string, else returning null and working directly on the file object (necessary for very large non-aggregated elections, at the risk of memory saturation).")]
    public static function exportElectionToCondorcetElectionFormat (
        #[FunctionParameter('Election with data')]
        Election $election,
        #[FunctionParameter('If true, will try to reduce number of lines, with quantifier for identical votes')]
        bool $aggregateVotes = true,
        #[FunctionParameter('Add the Number Of Seats parameters to the output')]
        bool $includeNumberOfSeats = true,
        #[FunctionParameter('Add the vote tags information if any. Don\'t work if $aggregateVotes is true')]
        bool $includeTags = true,
        ?\SplFileObject $file = null
    ): ?string
    {
        $r = '';
        $r .= '#/Candidates: ' . implode(' ; ', $election->getCandidatesListAsString())         . "\n";
        $r .= ($includeNumberOfSeats) ? '#/Number of Seats: ' . $election->getNumberOfSeats()   . "\n"  : null;
        $r .= '#/Implicit Ranking: ' . ($election->getImplicitRankingRule() ? 'true' : 'false') . "\n";
        $r .= '#/Weight Allowed: ' . ($election->isVoteWeightAllowed() ? 'true' : 'false')      . "\n";
        // $r .= "\n";

        if ($file) :
            $file->fwrite($r);
            $r = '';
        endif;

        if ($aggregateVotes) :
            $r .= "\n" . $election->getVotesListAsString(false);
            if ($file) : $file->fwrite($r); endif;
        else :
            foreach ($election->getVotesListGenerator() as $vote) :
                $line = "\n" . (($includeTags) ? ((string) $vote) : $vote->getSimpleRanking(null));

                if ($file) :
                    $file->fwrite($line);
                else :
                    $r .= $line;
                endif;
            endforeach;
        endif;

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

    public readonly array $candidates;
    public int $numberOfSeats;
    public bool $implicitRanking;
    public bool $voteWeight;

    public readonly int $invalidBlocksCount;

    // Read

    #[PublicAPI]
    #[Description("Read a Condorcet format file, usually using .cvotes file extension")]
    public function __construct (
        #[FunctionParameter('String, valid path to a text file or an object SplFileInfo or extending it like SplFileObject')]
        \SplFileInfo|string $input
    )
    {
        $input = ($input instanceof \SplFileInfo) ? $input : new \SplFileInfo($input);

        if ($input instanceof \SplFileObject) :
            $this->file = $input;
        elseif ($input->isFile() && $input->isReadable()) :
            $this->file = $input->openFile('r');
        else :
            throw new FileDoesNotExistException('Specified input file does not exist. path: '.$input);
        endif;

        unset($input); // Memory Optimization

        $this->file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $this->readParameters();
    }

    #[PublicAPI]
    #[Description("Add the data to an election object")]
    #[FunctionReturn("The election object")]
    #[Related("Tools\DavidHillFormat::setDataToAnElection", "Tools\DebianFormat::setDataToAnElection")]
    public function setDataToAnElection (
        #[FunctionParameter('Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.')]
        ?Election $election = null
    ): Election
    {
        if ($election === null) :
            $election = new Election;
        endif;

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
        foreach ($this->candidates as $oneCandidate) :
            $election->addCandidate($oneCandidate);
        endforeach;

        // Votes
        $this->file->rewind();
        $this->invalidBlocksCount = $election->parseVotesWithoutFail($this->file);

        return $election;
    }

    // Internal

    protected function readParameters (): void
    {
        $this->file->rewind();

        while (!$this->file->eof()) :
            $line = $this->file->fgets();
            $matches = [];

            if (preg_match(self::CANDIDATES_PATTERN, $line, $matches)) :
                $parse = $matches['candidates'];
                $parse = CondorcetUtil::prepareParse($parse, false);

                foreach ($parse as &$oneCandidate) :
                    $oneCandidate = new Candidate($oneCandidate);
                endforeach;

                $this->candidates = $parse;
            elseif (preg_match(self::SEATS_PATTERN, $line, $matches)) :
                $this->numberOfSeats = (int) $matches['seats'];
            elseif (preg_match(self::IMPLICIT_PATTERN, $line, $matches)) :
                $parse = strtolower($matches['implicitRanking']);
                 $this->implicitRanking =  $this->boolParser($parse);
            elseif (preg_match(self::WEIGHT_PATTERN, $line, $matches)) :
                $parse = strtolower($matches['weight']);
                $this->voteWeight = $this->boolParser($parse);
            elseif(!empty($line) && !\str_starts_with($line, '#')) :
                break;
            endif;
        endwhile;
    }

        protected function boolParser (string $parse): bool
        {
            return match ($parse) {
                'true' => true,
                'false' => false,
            };
        }
}
