<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Commands;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Constraints\NoTie;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use \Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ElectionCommand extends Command
{
    protected Election $election;
    protected string $candidates;
    protected string $votes;

    public static int $VotesPerMB = 100;

    // Internal Process
    protected bool $candidatesListIsWrite = false;
    protected bool $votesCountIsWrite = false;
    protected bool $pairwiseIsWrite = false;
    public ?string $SQLitePath = null;

    // TableFormat
    protected TableStyle $centerPadTypeStyle;

    protected function configure () : void
    {
        $this->setName('election')
            ->setAliases(['condorcet'])

            ->setDescription('Process an election')
            ->setHelp('This command takes candidates and votes as input. The output is the result of that election.')

            ->addOption(      'candidates', 'c'
                            , InputOption::VALUE_REQUIRED
                            , 'Candidates list file path or direct input'
            )
            ->addOption(      'votes', 'w'
                            , InputOption::VALUE_REQUIRED
                            , 'Votes list file path or direct input'
            )
            ->addOption(      'stats', 's'
                            , InputOption::VALUE_NONE
                            , 'Get detailed stats (equivalent to --show-pairwise and --method-stats)'
            )
            ->addOption(      'method-stats', null
                            , InputOption::VALUE_NONE
                            , 'Get detailed stats per method'
            )
            ->addOption(      'show-pairwise', 'p'
                            , InputOption::VALUE_NONE
                            , 'Get pairwise computation'
            )
            ->addOption(      'list-votes', 'l'
                            , InputOption::VALUE_NONE
                            , 'List registered votes'
            )
            ->addOption(      'natural-condorcet', 'r'
                            , InputOption::VALUE_NONE
                            , 'Print natural Condorcet winner / loser'
            )
            ->addOption(      'deactivate-implicit-ranking', 'i'
                            , InputOption::VALUE_NONE
                            , 'Deactivate implicit ranking'
            )
            ->addOption(      'allows-votes-weight', 'g'
                            , InputOption::VALUE_NONE
                            , 'Allows vote weight'
            )
            ->addOption(      'no-tie', 't'
                            , InputOption::VALUE_NONE
                            , 'Add no-tie constraint for vote'
            )
            ->addOption(      'seats', null
                            , InputOption::VALUE_REQUIRED
                            , 'Specify the number of seats for proportional methods'
            )

            ->addOption(      'deactivate-file-cache', null
                            , InputOption::VALUE_NONE
                            , "Don't use a disk cache for very large elections. Forces to work exclusively in RAM."
            )
            ->addOption(      'votes-per-mb', null
                            , InputOption::VALUE_REQUIRED
                            , "Adjust memory in case of failure. Default is 100. Try to lower it."
            )

            ->addArgument(
                             'methods'
                            , InputArgument::OPTIONAL | InputArgument::IS_ARRAY
                            , 'Methods to output'
            )
        ;
    }

    protected function initialize (InputInterface $input, OutputInterface $output) : void
    {
        // Initialize Style
        $this->centerPadTypeStyle = (new TableStyle())->setPadType(STR_PAD_BOTH);

        // Setup Memory
        if ($input->getOption('votes-per-mb') && ($NewVotesPerMB = (int) $input->getOption('votes-per-mb')) >= 1 ) :
            self::$VotesPerMB = $NewVotesPerMB;
        endif;

        // Setup Election Object
        $this->election = new Election;

            /// Implicit Ranking
            if ($input->getOption('deactivate-implicit-ranking')) :
                $this->election->setImplicitRanking(false);
            endif;

            // Allow Votes Weight
            if ($input->getOption('allows-votes-weight')) :
                $this->election->allowsVoteWeight(true);
            endif;

            // NoTie Constraint
            if ($input->getOption('no-tie')) :
                $this->election->addConstraint(NoTie::class);
            endif;

            if ($input->getOption('seats') && ($seats = (int) $input->getOption('seats')) >= 1 ) :
                $this->election->setNumberOfSeats($seats);
            endif;

        // Non-interactive candidates
        $this->candidates = $input->getOption('candidates') ?? '';

        // Non-interactive votes
        $this->votes = $input->getOption('votes') ?? '';
    }

    protected function interact (InputInterface $input, OutputInterface $output) : void
    {
        // Interactive Candidates
        if (empty($this->candidates)) :
            $helper = $this->getHelper('question');

            $c = 0;
            $registeringCandidates = [];

            while (true) :
                $question = new Question('Please register candidate N°'.++$c.' or press enter: ', null);
                $answer = $helper->ask($input, $output, $question);

                if ($answer === null) :
                    break;
                else :
                    $registeringCandidates[] = \str_replace(';', ' ', $answer);
                endif;
            endwhile;

            $this->candidates = \implode(';', $registeringCandidates);
        endif;

        // Interactive Votes
        if (empty($this->votes)) :
            $helper = $this->getHelper('question');

            $c = 0;
            $registeringvotes = [];

            while (true) :
                $question = new Question('Please register vote N°'.++$c.' or press enter: ', null);
                $answer = $helper->ask($input, $output, $question);

                if ($answer === null) :
                    break;
                else :
                    $registeringvotes[] = \str_replace(';', ' ', $answer);
                endif;
            endwhile;

            $this->votes = \implode(';', $registeringvotes);
        endif;
    }

    protected function execute (InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle ($input, $output);

        // Parse Votes & Candidates
        if ($file = $this->getFilePath($this->candidates)) :
            $this->election->parseCandidates($file, true);
        else :
            $this->election->parseCandidates($this->candidates);
        endif;

        // Define Callback
        $callBack = $this->useDataHandler($input);

        // Parses Votes
        if ($file = $this->getFilePath($this->votes)) :
            $this->election->parseVotesWithoutFail($file, true, $callBack);
        else :
            $this->election->parseVotesWithoutFail($this->votes, false, $callBack);
        endif;

        unset($callBack);

        // Summary
        $io->title('Summary');

        $output->write($this->election->countCandidates().' candidates(s) registered');
        $output->write('  ||  ');
        $output->writeln($this->election->countVotes().' vote(s) registered');
        $output->writeln('<info>==========================</>');

        $io->definitionList(
            ['Is vote weight allowed?' => $this->election->isVoteWeightAllowed() ? 'TRUE' : 'FALSE'],
            new TableSeparator(),
            ['Votes are evaluated according to the implicit ranking rule?' => $this->election->getImplicitRankingRule() ? 'TRUE' : 'FALSE'],
            new TableSeparator(),
            ['Is vote tie in rank allowed?' => \in_array(needle: NoTie::class, haystack: $this->election->getConstraints(), strict: true) ? 'TRUE' : 'FALSE']
        );

        // Input Sum Up
        if ($output->isVerbose()) :
            $this->sectionVerbose($io ,$input,$output);
        endif;

        if ($input->getOption('list-votes')) :
            $this->displayVotesCount($output);

            $this->displayVotesList($output);

            $io->newLine();
        endif;

        // Pairwise
        if ($input->getOption('show-pairwise') || $input->getOption('stats')) :
            $this->displayPairwise($output);
        endif;

        // Natural Condorcet
        if ($input->getOption('natural-condorcet')) :
            $io->section('Condorcet natural winner & loser');

            (new Table($output))
                ->setHeaderTitle('Natural Condorcet')
                ->setHeaders(['Type', 'Candidate'])
                ->setRows([
                            ['* Condorcet winner', ( $this->election->getCondorcetWinner()->getName() ?? 'NULL' )],
                            ['# Condorcet loser', ( $this->election->getCondorcetLoser()->getName() ?? 'NULL' )]
                ])

                ->render()
            ;

            $io->newLine();
        endif;


        // By Method

        $methods = $this->prepareMethods($input->getArgument('methods'));

        $io->title('Results per methods');

        foreach ($methods as $oneMethod) :

            $io->newLine();
            $io->section($oneMethod.' Method:');

            // Result
            $result = $this->election->getResult($oneMethod);

            if (!empty($options = $result->getMethodOptions())) :
                
                $rows = [];
                foreach ($options as $key => $value) :
                    $rows[] = [$key.':', $value];
                endforeach;

                (new Table($output))
                    ->setHeaderTitle('Configuration: '.$oneMethod)
                    ->setHeaders(['Variable', 'Value'])
                    ->setRows($rows)

                    ->setColumnStyle(0,$this->centerPadTypeStyle)
                    ->setColumnWidth(0, 20)
                    ->render()
                ;
            endif;

            (new Table($output))
                ->setHeaderTitle('Results: '.$oneMethod)
                ->setHeaders(['Rank', 'Candidates'])
                ->setRows($this->formatResultTable($result))

                ->setColumnStyle(0,$this->centerPadTypeStyle)
                ->setColumnWidth(0, 20)
                ->setColumnWidth(1, 20)
                ->render()
            ;

            // Stats
            if ($input->getOption('method-stats') || $input->getOption('stats')) :
                (new Table($output))
                    ->setHeaderTitle('Stats: '.$oneMethod)
                    ->setHeaders(['Stats'])
                    ->setRows([[preg_replace('#!!float (\d+)#', '\1.0', Yaml::dump($result->getStats(),100))]])

                    ->setColumnWidth(0, 43)
                    ->render()
                ;
            endif;

        endforeach;

        unset($result);

        // RM Sqlite Database if exist
        if ( ($SQLitePath = $this->SQLitePath) !== null) :
            unset($this->election);
            while(\gc_collect_cycles()); // Circular references are not really cleaned. Need to destroy PDO object for Windows.
            unlink($SQLitePath);
        endif;

        // Success
        $io->newLine();
        $io->success('Success');

        return 0;
    }

    protected function sectionVerbose (SymfonyStyle $io, InputInterface $input, OutputInterface $output) : void
    {
        $io->title('Detailed election input');

        $this->displayCandidatesList($output);
        $this->displayVotesCount($output);

        $io->newLine();
    }

    protected function displayCandidatesList (OutputInterface $output) : void
    {
        if (!$this->candidatesListIsWrite) :
            // Candidate List
            ($candidateTable = new Table($output))
                ->setHeaderTitle('Registered candidates')
                ->setHeaders(['Num', 'Candidate name'])

                ->setStyle($this->centerPadTypeStyle)
                ->setColumnWidth(0, 14)
            ;

            $candidate_num = 1;
            foreach ($this->election->getCandidatesListAsString() as $oneCandidate) :
                $candidateTable->addRow([$candidate_num++, $oneCandidate]);
            endforeach;

            $candidateTable->render();

            $this->candidatesListIsWrite = true;
        endif;
    }

    public function displayVotesCount (OutputInterface $output) : void
    {
        if (!$this->votesCountIsWrite) :
            // Votes Count
            ($votesStatsTable = new Table($output))
                ->setHeaderTitle('Stats - votes registration')
                ->setHeaders(['Stats', 'Value'])
                ->setColumnStyle(0,(new Tablestyle())->setPadType(\STR_PAD_LEFT))
            ;

            $votesStatsTable->addRow(['Count registered votes', $this->election->countValidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Count valid registered votes with constraints', $this->election->countValidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Count invalid registered votes with constraints', $this->election->countInvalidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Sum vote weight', $this->election->sumVotesWeight()]);
            $votesStatsTable->addRow(['Sum valid votes weight with constraints', $this->election->sumValidVotesWeightWithConstraints()]);

            $votesStatsTable->render();

            $this->votesCountIsWrite = true;
        endif;
    }

    public function displayVotesList (OutputInterface $output) : void
    {
        ($votesTable = new Table($output))
            ->setHeaderTitle('Registered votes list')
            ->setHeaders(['Vote Num.', 'Vote', 'Vote Weight', 'Vote Tags'])
        ;

        foreach ($this->election->getVotesValidUnderConstraintGenerator() as $voteKey => $oneVote) :
            $votesTable->addRow( [ ($voteKey + 1), $oneVote->getSimpleRanking($this->election, false), $oneVote->getWeight($this->election), \implode(',', $oneVote->getTags()) ] );
        endforeach;

        $votesTable->render();
    }

    public function displayPairwise (OutputInterface $output) : void
    {
        if (!$this->pairwiseIsWrite) :
            (new Table($output))
                ->setHeaderTitle('Pairwise')
                ->setHeaders(['For each candidate, show their win, null or lose'])
                ->setRows([[preg_replace('#!!float (\d+)#', '\1.0', Yaml::dump($this->election->getExplicitPairwise(),100))]])

                ->render()
            ;

            $this->pairwiseIsWrite= true;
        endif;
    }

    protected function prepareMethods (array $methodArgument) : array
    {
        if (empty($methodArgument)) :
            return [Condorcet::getDefaultMethod()::METHOD_NAME[0]];
        else :
            $methods = [];

            foreach ($methodArgument as $oneMethod) :
                if (\strtolower($oneMethod) === "all") :
                    $methods = Condorcet::getAuthMethods(false);
                    break;
                endif;

                if (Condorcet::isAuthMethod($oneMethod)) :
                    $method_name = Condorcet::getMethodClass($oneMethod)::METHOD_NAME[0];

                    if (!in_array(needle: $method_name, haystack: $methods, strict: true)) :
                        $methods[] = Condorcet::getMethodClass($oneMethod)::METHOD_NAME[0];
                    endif;
                endif;
            endforeach;

            return $methods;
        endif;
    }

    protected function formatResultTable (Result $result) : array
    {
        $resultArray = $result->getResultAsArray(true);

        foreach ($resultArray as $rank => &$line) :
            if (\is_array($line)) :
                $line = \implode(',', $line);
            endif;

            if ($rank === 1 && \count($result[1]) === 1 && $result[1][0] === $result->getCondorcetWinner()) :
                $line = $line.'*';
            elseif ($rank === \max(\array_keys($resultArray)) && \count($result[\max(\array_keys($resultArray))]) === 1 && $result[\max(\array_keys($resultArray))][0] === $result->getCondorcetLoser()) :
                $line = $line.'#';
            endif;

            $line = [$rank,$line];
        endforeach;


        $last_rank = \max(\array_keys($resultArray));

        return $resultArray;
    }

    protected function getFilePath (string $path)  : ?string
    {
        if ($this->isAbsolute($path) && \is_file($path)) :
            return $path;
        else :
            return (\is_file($file = \getcwd().\DIRECTORY_SEPARATOR.$path)) ? $file : null;
        endif;
        ;
    }

    protected function isAbsolute (string $path) : bool
    {
        return empty($path) ? false :   (   \strspn($path, '/\\', 0, 1) ||
                                            ( \strlen($path) > 3 && \ctype_alpha($path[0]) && ':' === $path[1] && \strspn($path, '/\\', 2, 1) )
                                        );
    }

    protected function useDataHandler (InputInterface $input) : ?\Closure
    {
        if ( $input->getOption('deactivate-file-cache') || !\class_exists('\PDO') || !\in_array(needle: 'sqlite', haystack: \PDO::getAvailableDrivers(), strict: true) ) :
            return null;
        else :
            $election = $this->election;
            $SQLitePath = &$this->SQLitePath;

            $memory_limit = (int) \preg_replace('`[^0-9]`', '', \ini_get('memory_limit'));
            $vote_in_memory_limit = self::$VotesPerMB * $memory_limit;

            $callBack = function (int $inserted_votes_count) use ($election, $vote_in_memory_limit, &$SQLitePath) : bool {
                if (  $inserted_votes_count > $vote_in_memory_limit ) :

                    if ( \file_exists( $SQLitePath = \getcwd().'/condorcet-bdd.sqlite' ) ) :
                        \unlink($SQLitePath);
                    endif;

                    $election->setExternalDataHandler( new PdoHandlerDriver (new \PDO ('sqlite:'.$SQLitePath,'','',[\PDO::ATTR_PERSISTENT => false]), true) );
                    return false; // No, stop next iteration
                else :
                    return true; // Yes, continue
                endif;
            };

            return $callBack;
        endif;
    }

}