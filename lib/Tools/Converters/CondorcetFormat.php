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

class CondorcetFormat implements ConverterInterface
{
    // Const
    protected const CANDIDATES_PATTERN  = '/^#\/Candidates:(?<candidates>.+)$/mi';
    protected const SEATS_PATTERN       = '/^#\/Number of Seats: *(?<seats>[0-9]+) *$/mi';
    protected const IMPLICIT_PATTERN    = '/^#\/Implicit Ranking: *(?<implicitRanking>(true|false)) *$/mi';

    // Properties
    protected \SplFileObject $file;

    public readonly array $candidates;
    public int $numberOfSeats;
    public bool $implicitRanking = true;

    public readonly int $invalidBlocksCount;


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
            $election->setImplicitRanking($this->implicitRanking);


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

                 $this->implicitRanking = match ($parse) {
                    'true' => true,
                    'false' => false,
                 };
            elseif(!empty($line) && !\str_starts_with($line, '#')) :
                break;
            endif;
        endwhile;
    }
}
