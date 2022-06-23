<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Commands;

use CondorcetPHP\Condorcet\Algo\Tools\StvQuotas;
use CondorcetPHP\Condorcet\Constraints\NoTie;
use CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\PdoDriver\PdoHandlerDriver;
use CondorcetPHP\Condorcet\{Condorcet, Election, Result};
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use CondorcetPHP\Condorcet\Throwable\{FileDoesNotExistException, VoteConstraintException};
use Symfony\Component\Console\Helper\{Table, TableSeparator, TableStyle};
use CondorcetPHP\Condorcet\Tools\Converters\{CondorcetElectionFormat, DavidHillFormat, DebianFormat};
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'election',
    description: 'Process an election',
    hidden: false,
    aliases: ['condorcet']
)]
class ElectionCommand extends Command
{
    protected Election $election;
    protected ?string $candidates;
    protected ?string $votes;

    protected ?string $CondorcetElectionFormatPath;
    protected ?string $DebianFormatPath;
    protected ?string $DavidHillFormatPath;

    public static int $VotesPerMB = 100;

    // Internal Process
    protected bool $candidatesListIsWrite = false;
    protected bool $votesCountIsWrite = false;
    protected bool $pairwiseIsWrite = false;
    public ?string $SQLitePath = null;

    // TableFormat & Terminal
    protected TableStyle $centerPadTypeStyle;
    protected Terminal $terminal;

    // Debug
    public static ?string $forceIniMemoryLimitTo = null;

    protected function configure(): void
    {
        $this->setHelp('This command takes candidates and votes as input. The output is the result of that election.')

            ->addOption(
                name: 'candidates',
                shortcut: 'c',
                mode: InputOption::VALUE_REQUIRED,
                description: 'Candidates list file path or direct input',
            )
            ->addOption(
                name: 'votes',
                shortcut: 'w',
                mode: InputOption::VALUE_REQUIRED,
                description: 'Votes list file path or direct input',
            )
            ->addOption(
                name: 'import-condorcet-election-format',
                mode: InputOption::VALUE_REQUIRED,
                description: 'File path. Setup an election and his data from a Condorcet Election file as defined as standard on https://github.com/CondorcetPHP/CondorcetElectionFormat . Other parameters from the command line argument have the priority if set. Other votes can be added with the --vote argument, other candidates can\'t be added.',
            )
            ->addOption(
                name: 'import-debian-format',
                mode: InputOption::VALUE_REQUIRED,
                description: 'File path. Setup an election and his data from a Debian tally file. Other votes can be added with the --vote argument, other candidates can\'t be added.',
            )
            ->addOption(
                name: 'import-david-hill-format',
                mode: InputOption::VALUE_REQUIRED,
                description: 'File path. Setup an election and his data from a Debian tally file. Other votes can be added with the --vote argument, other candidates can\'t be added.',
            )


            ->addOption(
                name: 'stats',
                shortcut: 's',
                mode: InputOption::VALUE_NONE,
                description: 'Get detailed stats (equivalent to --show-pairwise and --method-stats)',
            )
            ->addOption(
                name: 'method-stats',
                mode: InputOption::VALUE_NONE,
                description: 'Get detailed stats per method',
            )
            ->addOption(
                name: 'show-pairwise',
                shortcut: 'p',
                mode: InputOption::VALUE_NONE,
                description: 'Get pairwise computation',
            )
            ->addOption(
                name: 'list-votes',
                shortcut: 'l',
                mode: InputOption::VALUE_NONE,
                description: 'List registered votes',
            )
            ->addOption(
                name: 'natural-condorcet',
                shortcut: 'r',
                mode: InputOption::VALUE_NONE,
                description: 'Print natural Condorcet winner / loser',
            )
            ->addOption(
                name: 'deactivate-implicit-ranking',
                shortcut: 'i',
                mode: InputOption::VALUE_NONE,
                description: 'Deactivate implicit ranking',
            )
            ->addOption(
                name: 'allows-votes-weight',
                shortcut: 'g',
                mode: InputOption::VALUE_NONE,
                description: 'Allows vote weight',
            )
            ->addOption(
                name: 'no-tie',
                shortcut: 't',
                mode: InputOption::VALUE_NONE,
                description: 'Add no-tie constraint for vote',
            )

            ->addOption(
                name: 'seats',
                mode: InputOption::VALUE_REQUIRED,
                description: 'Specify the number of seats for proportional methods',
            )
            ->addOption(
                name: 'quota',
                mode: InputOption::VALUE_REQUIRED,
                description: 'Quota to be used for STV compatible methods',
            )

            ->addOption(
                name: 'deactivate-file-cache',
                mode: InputOption::VALUE_NONE,
                description: "Don't use a disk cache for very large elections. Forces to work exclusively in RAM.",
            )
            ->addOption(
                name: 'votes-per-mb',
                mode: InputOption::VALUE_REQUIRED,
                description: "Adjust memory in case of failure. Default is 100. Try to lower it.",
            )

            ->addArgument(
                name: 'methods',
                mode: InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                description: 'Methods to output',
            )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // Initialize Style & Terminal
        $this->centerPadTypeStyle = (new TableStyle)->setPadType(STR_PAD_BOTH);
        $this->terminal = new Terminal;

        // Setup Memory
        if ($input->getOption('votes-per-mb') && ($NewVotesPerMB = (int) $input->getOption('votes-per-mb')) >= 1) {
            self::$VotesPerMB = $NewVotesPerMB;
        }

        // Setup Election Object
        $this->election = new Election;

        // Parameters
        $this->setUpParameters($input);
        $this->ini_memory_limit = (self::$forceIniMemoryLimitTo === null) ? \ini_get('memory_limit') : self::$forceIniMemoryLimitTo;

        // Non-interactive candidates
        $this->candidates = $input->getOption('candidates') ?? null;

        // Non-interactive votes
        $this->votes = $input->getOption('votes') ?? null;

        $this->CondorcetElectionFormatPath = $input->getOption('import-condorcet-election-format') ?? null;
        $this->DebianFormatPath = $input->getOption('import-debian-format') ?? null;
        $this->DavidHillFormatPath = $input->getOption('import-david-hill-format') ?? null;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (empty($this->CondorcetElectionFormatPath) && empty($this->DebianFormatPath) && empty($this->DavidHillFormatPath)) {
            // Interactive Candidates
            if (empty($this->candidates)) {
                $helper = $this->getHelper('question');

                $c = 0;
                $registeringCandidates = [];

                while (true) {
                    $question = new Question('Please register candidate N°'.++$c.' or press enter: ', null);
                    $answer = $helper->ask($input, $output, $question);

                    if ($answer === null) {
                        break;
                    } else {
                        $registeringCandidates[] = \str_replace(';', ' ', $answer);
                    }
                }

                $this->candidates = \implode(';', $registeringCandidates);
            }

            // Interactive Votes
            if (empty($this->votes)) {
                $helper = $this->getHelper('question');

                $c = 0;
                $registeringvotes = [];

                while (true) {
                    $question = new Question('Please register vote N°'.++$c.' or press enter: ', null);
                    $answer = $helper->ask($input, $output, $question);

                    if ($answer === null) {
                        break;
                    } else {
                        $registeringvotes[] = \str_replace(';', ' ', $answer);
                    }
                }

                $this->votes = \implode(';', $registeringvotes);
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Define Callback
        $callBack = $this->useDataHandler($input);

        // Setup Elections, candidates and votes from Condorcet Election Format
        if ($input->getOption('import-condorcet-election-format')) {
            $this->parseFromCondorcetElectionFormat($callBack);
            $this->setUpParameters($input); # Do it again, because command line parameters have priority
        }

        if ($input->getOption('import-debian-format')) {
            $this->election->setNumberOfSeats(1); # Debian must be 1
            $this->parseFromDebianFormat();
            $this->setUpParameters($input); # Do it again, because command line parameters have priority
        }

        if ($input->getOption('import-david-hill-format')) {
            $this->parseFromDavidHillFormat();
            $this->setUpParameters($input); # Do it again, because command line parameters have priority
        }

        // Parse Votes & Candidates from classicals inputs
        !empty($this->candidates) && $this->parseFromCandidatesArguments();
        !empty($this->votes) && $this->parseFromVotesArguments($callBack);

        unset($callBack);

        // Summary
        $io = new SymfonyStyle($input, $output);

        $io->title('Summary');

        $output->write($this->election->countCandidates().' candidates(s) registered');
        $output->write('  ||  ');
        $output->writeln($this->election->countVotes().' vote(s) registered');

        if ($output->isDebug()) {
            $io->info('Votes per Mb: '.self::$VotesPerMB);
            $io->info('Db is used: '.((empty($this->SQLitePath)) ? 'no' : 'yes, using path: '.$this->SQLitePath));

            $output->writeln($this->election->countVotes().' vote(s) registered');
        }

        $output->writeln('<info>==========================</>');

        $io->definitionList(
            ['Is vote weight allowed?' => $this->election->isVoteWeightAllowed() ? 'TRUE' : 'FALSE'],
            new TableSeparator,
            ['Votes are evaluated according to the implicit ranking rule?' => $this->election->getImplicitRankingRule() ? 'TRUE' : 'FALSE'],
            new TableSeparator,
            ['Is vote tie in rank allowed?' => \in_array(needle: NoTie::class, haystack: $this->election->getConstraints(), strict: true) ? 'FALSE' : 'TRUE']
        );

        // Input Sum Up
        if ($output->isVerbose()) {
            $this->sectionVerbose($io, $output);
        }

        if ($input->getOption('list-votes')) {
            $this->displayVotesCount($output);

            $this->displayVotesList($output);

            $io->newLine();
        }

        // Pairwise
        if ($input->getOption('show-pairwise') || $input->getOption('stats')) {
            $this->displayPairwise($output);
        }

        // Natural Condorcet
        if ($input->getOption('natural-condorcet')) {
            $io->section('Condorcet natural winner & loser');

            (new Table($output))
                ->setHeaderTitle('Natural Condorcet')
                ->setHeaders(['Type', 'Candidate'])
                ->setRows([
                            ['* Condorcet winner', (string) ($this->election->getCondorcetWinner() ?? 'NULL')],
                            ['# Condorcet loser', (string) ($this->election->getCondorcetLoser() ?? 'NULL')]
                ])

                ->render()
            ;

            $io->newLine();
        }


        // By Method

        $methods = $this->prepareMethods($input->getArgument('methods'));

        $io->title('Results per methods');

        foreach ($methods as $oneMethod) {
            $io->newLine();
            $io->section($oneMethod['name'].' Method:');

            if (isset($oneMethod['class']::$optionQuota) && $input->getOption('quota') !== null) {
                $this->election->setMethodOption($oneMethod['class'], 'Quota', StvQuotas::make($input->getOption('quota')));
            }

            // Result
            $result = $this->election->getResult($oneMethod['name']);

            if (!empty($options = $result->getMethodOptions())) {
                $rows = [];
                foreach ($options as $key => $value) {
                    if ($value instanceof \BackedEnum) {
                        $value = $value->value;
                    } elseif (\is_array($value)) {
                        $value = implode(' / ', $value);
                    }

                    $rows[] = [$key.':', $value];
                }

                (new Table($output))
                    ->setHeaderTitle('Configuration: '.$oneMethod['name'])
                    ->setHeaders(['Variable', 'Value'])
                    ->setRows($rows)

                    ->setColumnStyle(0, $this->centerPadTypeStyle)
                    ->setColumnWidth(0, 0)
                    ->render()
                ;
            }

            (new Table($output))
                ->setHeaderTitle('Results: '.$oneMethod['name'])
                ->setHeaders(['Rank', 'Candidates'])
                ->setRows($this->formatResultTable($result))

                ->setColumnStyle(0, $this->centerPadTypeStyle)

                ->setColumnWidth(0, 30)
                ->setColumnWidth(1, 100)
                ->setColumnMaxWidth(1, ($this->terminal->getWidth() - 30))

                ->render()
            ;

            // Stats
            if ($input->getOption('method-stats') || $input->getOption('stats')) {
                (new Table($output))
                    ->setHeaderTitle('Stats: '.$oneMethod['name'])
                    ->setHeaders(['Stats'])
                    ->setRows([[preg_replace('#!!float (\d+)#', '\1.0', Yaml::dump($result->getStats(), 100))]])

                    ->setColumnWidth(0, 43)
                    ->render()
                ;
            }
        }

        unset($result);

        // RM Sqlite Database if exist
        /**
         * @infection-ignore-all
         */
        if (($SQLitePath = $this->SQLitePath) !== null) {
            unset($this->election);
            unlink($SQLitePath);
        }

        // Success
        $io->newLine();
        $io->success('Success');

        return Command::SUCCESS;
    }

    protected function sectionVerbose(SymfonyStyle $io, OutputInterface $output): void
    {
        $io->title('Detailed election input');

        $this->displayCandidatesList($output);
        $this->displayVotesCount($output);

        $io->newLine();
    }

    protected function displayCandidatesList(OutputInterface $output): void
    {
        if (!$this->candidatesListIsWrite) {
            // Candidate List
            ($candidateTable = new Table($output))
                ->setHeaderTitle('Registered candidates')
                ->setHeaders(['Num', 'Candidate name'])

                ->setStyle($this->centerPadTypeStyle)
                ->setColumnWidth(0, 14)
            ;

            $candidate_num = 1;
            foreach ($this->election->getCandidatesListAsString() as $oneCandidate) {
                $candidateTable->addRow([$candidate_num++, $oneCandidate]);
            }

            $candidateTable->render();

            $this->candidatesListIsWrite = true;
        }
    }

    public function displayVotesCount(OutputInterface $output): void
    {
        if (!$this->votesCountIsWrite) {
            // Votes Count
            ($votesStatsTable = new Table($output))
                ->setHeaderTitle('Stats - votes registration')
                ->setHeaders(['Stats', 'Value'])
                ->setColumnStyle(0, (new Tablestyle)->setPadType(\STR_PAD_LEFT))
            ;

            $votesStatsTable->addRow(['Count registered votes', $this->election->countValidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Count valid registered votes with constraints', $this->election->countValidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Count invalid registered votes with constraints', $this->election->countInvalidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Sum vote weight', $this->election->sumVotesWeight()]);
            $votesStatsTable->addRow(['Sum valid votes weight with constraints', $this->election->sumValidVotesWeightWithConstraints()]);

            $votesStatsTable->render();

            $this->votesCountIsWrite = true;
        }
    }

    public function displayVotesList(OutputInterface $output): void
    {
        ($votesTable = new Table($output))
            ->setHeaderTitle('Registered votes list')
            ->setHeaders(['Vote Num.', 'Vote', 'Vote Weight', 'Vote Tags'])

            ->setColumnMaxWidth(1, ($this->terminal->getWidth() - 50))
        ;

        foreach ($this->election->getVotesValidUnderConstraintGenerator() as $voteKey => $oneVote) {
            $votesTable->addRow([ ($voteKey + 1), $oneVote->getSimpleRanking($this->election, false), $oneVote->getWeight($this->election), \implode(',', $oneVote->getTags()) ]);
        }

        $votesTable->render();
    }

    public function displayPairwise(OutputInterface $output): void
    {
        if (!$this->pairwiseIsWrite) {
            (new Table($output))
                ->setHeaderTitle('Pairwise')
                ->setHeaders(['For each candidate, show their win, null or lose'])
                ->setRows([[preg_replace('#!!float (\d+)#', '\1.0', Yaml::dump($this->election->getExplicitPairwise(), 100))]])

                ->render()
            ;

            $this->pairwiseIsWrite= true;
        }
    }

    # Processing

    protected function prepareMethods(array $methodArgument): array
    {
        if (empty($methodArgument)) {
            return [['name' => Condorcet::getDefaultMethod()::METHOD_NAME[0], 'class' => Condorcet::getDefaultMethod()]];
        } else {
            $methods = [];

            foreach ($methodArgument as $oneMethod) {
                if (\strtolower($oneMethod) === "all") {
                    $methods = Condorcet::getAuthMethods(false);
                    $methods = array_map(fn ($m) => ['name' => $m, 'class' => Condorcet::getMethodClass($m)], $methods);
                    break;
                }

                if (Condorcet::isAuthMethod($oneMethod)) {
                    $method_class = Condorcet::getMethodClass($oneMethod);
                    $method_name = $method_class::METHOD_NAME[0];

                    if (!in_array(needle: $method_name, haystack: $methods, strict: true)) {
                        $methods[] = ['name' => $method_name, 'class' => $method_class];
                    }
                }
            }

            return $methods;
        }
    }

    protected function setUpParameters(InputInterface $input): void
    {
        /// Implicit Ranking
        if ($input->getOption('deactivate-implicit-ranking')) {
            $this->election->setImplicitRanking(false);
        }

        // Allow Votes Weight
        if ($input->getOption('allows-votes-weight')) {
            $this->election->allowsVoteWeight(true);
        }

        if ($input->getOption('seats') && ($seats = (int) $input->getOption('seats')) >= 1) {
            $this->election->setNumberOfSeats($seats);
        }

        try {
            if ($input->getOption('no-tie')) {
                $this->election->addConstraint(NoTie::class);
            }
        } catch (VoteConstraintException $e) {
            str_contains($e->getMessage(), 'class is already registered') || throw $e;
        }
    }

    protected function parseFromCandidatesArguments(): void
    {
        if ($file = $this->getFilePath($this->candidates)) {
            $this->election->parseCandidates($file, true);
        } else {
            $this->election->parseCandidates($this->candidates);
        }
    }

    protected function parseFromVotesArguments(\Closure $callBack): void
    {
        // Parses Votes
        if ($file = $this->getFilePath($this->votes)) {
            $this->election->parseVotesWithoutFail(input: $file, isFile: true, callBack: $callBack);
        } else {
            $this->election->parseVotesWithoutFail(input: $this->votes, isFile: false, callBack: $callBack);
        }
    }

    protected function parseFromCondorcetElectionFormat(\Closure $callBack): void
    {
        $file = $this->getFilePath($this->CondorcetElectionFormatPath);

        if ($file !== null) {
            (new CondorcetElectionFormat($file))->setDataToAnElection($this->election, $callBack);
        } else {
            throw new FileDoesNotExistException('File does not exist, path: '.$this->CondorcetElectionFormatPath);
        }
    }

    protected function parseFromDebianFormat(): void
    {
        $file = $this->getFilePath($this->DebianFormatPath);

        if ($file !== null) {
            (new DebianFormat($file))->setDataToAnElection($this->election);
        } else {
            throw new FileDoesNotExistException('File does not exist, path: '.$this->CondorcetElectionFormatPath);
        }
    }

    protected function parseFromDavidHillFormat(): void
    {
        $file = $this->getFilePath($this->DavidHillFormatPath);

        if ($file !== null) {
            (new DavidHillFormat($file))->setDataToAnElection($this->election);
        } else {
            throw new FileDoesNotExistException('File does not exist, path: '.$this->CondorcetElectionFormatPath);
        }
    }

    protected function formatResultTable(Result $result): array
    {
        $resultArray = $result->getResultAsArray(true);

        foreach ($resultArray as $rank => &$line) {
            if (\is_array($line)) {
                $line = \implode(',', $line);
            }

            if ($rank === 1 && \count($result[1]) === 1 && $result[1][0] === $result->getCondorcetWinner()) {
                $line = $line.'*';
            } elseif ($rank === \max(\array_keys($resultArray)) && \count($result[\max(\array_keys($resultArray))]) === 1 && $result[\max(\array_keys($resultArray))][0] === $result->getCondorcetLoser()) {
                $line = $line.'#';
            }

            $line = [$rank,$line];
        }


        $last_rank = \max(\array_keys($resultArray));

        return $resultArray;
    }

    protected function getFilePath(string $path): ?string
    {
        if ($this->isAbsolute($path) && \is_file($path)) {
            return $path;
        } else {
            return (\is_file($file = \getcwd().\DIRECTORY_SEPARATOR.$path)) ? $file : null;
        }
        ;
    }

    protected function isAbsolute(string $path): bool
    {
        return empty($path) ? false : (
            \strspn($path, '/\\', 0, 1) ||
                                            (\strlen($path) > 3 && \ctype_alpha($path[0]) && ':' === $path[1] && \strspn($path, '/\\', 2, 1))
        );
    }

    protected function useDataHandler(InputInterface $input): ?\Closure
    {
        if ($input->getOption('deactivate-file-cache') || !\class_exists('\PDO') || !\in_array(needle: 'sqlite', haystack: \PDO::getAvailableDrivers(), strict: true)) {
            return null;
        } else {
            $election = $this->election;
            $SQLitePath = &$this->SQLitePath;

            $memory_limit = (int) \preg_replace('`[^0-9]`', '', $this->ini_memory_limit);
            $vote_in_memory_limit = self::$VotesPerMB * $memory_limit;

            $callBack = static function (int $inserted_votes_count) use ($election, $vote_in_memory_limit, &$SQLitePath): bool {
                if ($inserted_votes_count > $vote_in_memory_limit) {

                    /**
                     * @infection-ignore-all
                     */
                    if (\file_exists($SQLitePath = \getcwd().'/condorcet-bdd.sqlite')) {
                        \unlink($SQLitePath);
                    }

                    /**
                     * @infection-ignore-all
                     */
                    $election->setExternalDataHandler(new PdoHandlerDriver(new \PDO('sqlite:'.$SQLitePath, '', '', [\PDO::ATTR_PERSISTENT => false]), true));

                    return false; // No, stop next iteration
                } else {
                    return true; // Yes, continue
                }
            };

            return $callBack;
        }
    }
}
