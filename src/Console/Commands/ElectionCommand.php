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
use CondorcetPHP\Condorcet\{Condorcet, Election};
use CondorcetPHP\Condorcet\Algo\StatsVerbosity;
use CondorcetPHP\Condorcet\Console\Helper\{CommandInputHelper, FormaterHelper};
use CondorcetPHP\Condorcet\Console\Style\CondorcetStyle;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use CondorcetPHP\Condorcet\Throwable\{FileDoesNotExistException, VoteConstraintException};
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use CondorcetPHP\Condorcet\Timer\{Chrono, Manager};
use Symfony\Component\Console\Helper\{Table, TableSeparator, TableStyle};
use CondorcetPHP\Condorcet\Tools\Converters\{DavidHillFormat, DebianFormat};
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
use Random\Randomizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Completion\{CompletionInput, CompletionSuggestions};
use Symfony\Component\Console\Output\OutputInterface;
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
    protected ?Election $election;
    protected ?string $candidates;
    protected ?string $votes;
    protected bool $displayMethodsStats = false;

    protected ?string $CondorcetElectionFormatPath;
    protected ?string $DebianFormatPath;
    protected ?string $DavidHillFormatPath;

    public static int $VotesPerMB = 100;
    protected string $iniMemoryLimit;
    protected int $maxVotesInMemory;

    // Internal Process
    protected bool $candidatesListIsWrite = false;
    protected bool $votesCountIsWrite = false;
    protected bool $pairwiseIsWrite = false;
    public ?string $SQLitePath = null;

    // TableFormat & Terminal
    protected Terminal $terminal;
    protected CondorcetStyle $io;

    // Debug
    public static ?string $forceIniMemoryLimitTo = null;
    protected Manager $timer;

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
                description: 'File path. Setup an election and his data from a Condorcet Election file as defined as standard on https://github.com/CondorcetVote/CondorcetElectionFormat . Other parameters from the command line argument have the priority if set. Other votes can be added with the --vote argument, other candidates can\'t be added.',
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
                description: 'Adjust memory in case of failure. Default is 100. Try to lower it.',
            )

            ->addArgument(
                name: 'methods',
                mode: InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                description: 'Methods to output',
            )
        ;
    }

    #[\Override]
    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestOptionValuesFor('quota')) {
            $r = [];

            foreach (StvQuotas::cases() as $case) {
                $r[] = $case->name;
            }

            $suggestions->suggestValues($r);
        } elseif ($input->mustSuggestArgumentValuesFor('methods')) {
            $suggestions->suggestValues(Condorcet::getAuthMethods());
        }
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // Initialize Style & Terminal
        $this->io = new CondorcetStyle($input, $output);
        $this->terminal = new Terminal;

        // Setup Timer Manager
        $this->timer = new Manager;

        // Setup Memory
        if ($input->getOption('votes-per-mb') && ($NewVotesPerMB = (int) $input->getOption('votes-per-mb')) >= 1) {
            self::$VotesPerMB = $NewVotesPerMB;
        }

        // Setup Election Object
        $this->election = new Election;
        $this->displayMethodsStats = $input->getOption('method-stats') || $input->getOption('stats');
        $this->election->setStatsVerbosity($this->displayMethodsStats ? StatsVerbosity::HIGH : StatsVerbosity::NONE);

        // Parameters
        $this->setUpParameters($input);
        $this->iniMemoryLimit = (string) (self::$forceIniMemoryLimitTo === null) ? \ini_get('memory_limit') : self::$forceIniMemoryLimitTo;

        // Non-interactive candidates
        $this->candidates = $input->getOption('candidates') ?? null;

        // Non-interactive votes
        $this->votes = $input->getOption('votes') ?? null;

        $this->CondorcetElectionFormatPath = $input->getOption('import-condorcet-election-format') ?? null;
        $this->DebianFormatPath = $input->getOption('import-debian-format') ?? null;
        $this->DavidHillFormatPath = $input->getOption('import-david-hill-format') ?? null;

        // Logo
        $this->io->newLine();
        $this->io->logo($this->terminal->getWidth());
        $this->io->newLine();

        // Header
        $this->io->version();
        $this->io->inlineSeparator();
        $this->io->author(Condorcet::AUTHOR);
        $this->io->inlineSeparator();
        $this->io->homepage(Condorcet::HOMEPAGE);
        $this->io->newLine(2);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (empty($this->CondorcetElectionFormatPath) && empty($this->DebianFormatPath) && empty($this->DavidHillFormatPath)) {
            // Interactive Candidates
            if (empty($this->candidates)) {
                $this->io->title('Enter the candidates');
                $this->io->instruction('Candidates', 'Enter each candidate names');

                $registeringCandidates = [];

                while (true) {
                    $answer = $this->io->ask('Please register candidate N°<fg=magenta>' . (\count($registeringCandidates) + 1) . '</> <continue>(or press enter to continue)</>');

                    if ($answer === null) {
                        break;
                    } else {
                        array_push($registeringCandidates, ...explode(';', $answer));
                        $registeringCandidates = array_unique($registeringCandidates, \SORT_REGULAR);
                    }
                }

                $this->candidates = implode(';', $registeringCandidates);
            }

            // Implicit mod
            if ($input->getOption('deactivate-implicit-ranking') === false) {
                $implicitAnswer = $this->io->confirm(
                    question: 'Should the votes be interpreted implicitly (candidates not mentioned come last)?',
                    default: true
                );

                $this->election->setImplicitRanking($implicitAnswer);
            }

            // Interactive Votes
            if (empty($this->votes)) {
                $this->io->title('Enter the votes');
                $this->io->instruction('Format', 'Candidate B > CandidateName D > CandidateName C = CandidateName A');

                $registeringVotes = [];

                while (true) {
                    $answer = $this->io->ask('Please register vote N°<fg=magenta>' . (\count($registeringVotes) + 1) . '</> <continue>(or press enter to continue)</>');

                    if ($answer === null) {
                        break;
                    } else {
                        array_push($registeringVotes, ...explode(';', $answer));
                    }
                }

                $this->votes = implode(';', $registeringVotes);
            }

            // Interactive Methods
            if (empty($input->getArgument('methods'))) {
                $this->io->title('Enter the methods');
                $this->io->instruction('Voting methods', 'Choose by entering their numbers separated by commas. Press enter for the default method.');

                $registeringMethods = [];

                $authMehods = Condorcet::getAuthMethods();
                $authMehods = array_merge(['ALL'], $authMehods);

                $registeringMethods = $this->io->choice(
                    question: 'Select methods',
                    choices: $authMehods,
                    default: Condorcet::getDefaultMethod()::METHOD_NAME[0],
                    multiSelect: true
                );

                $input->setArgument('methods', $registeringMethods);
            }

            if (empty($input->getOption('seats'))) {
                $hasProportionalMethods = false;
                $methods = FormaterHelper::prepareMethods($input->getArgument('methods'));

                foreach ($methods as $oneMethod) {
                    if ($oneMethod['class']::IS_PROPORTIONAL) {
                        $hasProportionalMethods = true;
                        break;
                    }
                }

                if ($hasProportionalMethods) {
                    $this->io->instruction('Number of Seats', 'Some of the method(s) chosen are proportional and require a number of seats.');

                    $answer = $this->io->ask('Number of seats to fill', (string) 100, static function ($answer): string {
                        if (!is_numeric($answer)) {
                            throw new CondorcetInternalException('Seats must be numeric');
                        }

                        if (($answer = (int) $answer) < 1) {
                            throw new CondorcetInternalException('Seats must be a natural number (positive)');
                        }

                        return (string) $answer;
                    });

                    $input->setOption('seats', $answer);
                    $this->election->setNumberOfSeats((int) $answer);
                }
            }
        }
    }

    protected function importInputsData(InputInterface $input): void
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
        if (!empty($this->candidates)) {
            $this->parseFromCandidatesArguments();
        }
        if (!empty($this->votes)) {
            $this->parseFromVotesArguments($callBack);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Start Timer Chrono
        $chrono = new Chrono($this->timer, 'CondorcetConsoleCommand');

        // Import Inputs
        $this->importInputsData($input);

        // Inputs Display Section
        $this->displayInputsSection();

        // Debug Display Section
        if ($output->isDebug()) {
            $this->displayDebugSection();
        }

        // Configuration Display Section
        $this->displayConfigurationSection();

        // Verbose Display Section
        if ($output->isVerbose() || $input->getOption('list-votes')) {
            $this->displayDetailedElectionInputsSection($input, $output);
        }

        // Warning if candidates count = 1
        if ($this->election->countCandidates() === 1) {
            $this->io->warning("There is only 1 candidate in this election named \"{$this->election->getCandidatesList()[0]->name}\". Check your input and make sure to separate candidates with semicolons.");
        }

        // Pairwise Display Section
        if ($input->getOption('show-pairwise') || $input->getOption('stats')) {
            $this->displayPairwiseSection($output);
            $this->io->newLine();
        }

        // NaturalCondorcet Dislay Section
        $this->displayNaturalCondorcet($input, $output);

        // MethodsResults Display Section
        $this->io->newLine();
        $this->io->title('Results per methods');
        $this->displayMethodsResultSection($input, $output);


        // RM Sqlite Database if exist
        /**
         * @infection-ignore-all
         */
        if (($SQLitePath = $this->SQLitePath) !== null) {
            $this->election = null;
            unlink($SQLitePath);
        }

        // Timer
        unset($chrono);
        $this->displayTimerSection();

        return Command::SUCCESS;
    }

    protected function displayInputsSection(): void
    {
        $messageCandidates = "<condor1>{$this->election->countCandidates()} candidate" . ($this->election->countCandidates() > 1 ? 's' : '') . ' registered</>';
        $messageVotes = '<condor2>' . number_format($this->election->countVotes(), thousands_separator: ' ') . ' vote' . ($this->election->countVotes() > 1 ? 's' : '') . ' registered</>';

        $this->io->writeln('<comment>' . str_repeat('-', mb_strlen($messageCandidates . $messageVotes) + 4 - 24) . '</comment>');
        $this->io->write($messageCandidates);
        $this->io->inlineSeparator();
        $this->io->writeln($messageVotes);
        $this->io->newLine();
    }

    protected function displayDebugSection(): void
    {
        $this->io->newLine();
        $this->io->title('Debug - External Handler Informations');

        $this->io->definitionList(
            ['Ini max_memory' => $this->iniMemoryLimit],
            ['Votes per Mb' => self::$VotesPerMB],
            ['Db is used' => (empty($this->SQLitePath)) ? 'no' : 'yes, using path: ' . $this->SQLitePath],
            ['Max Votes in Memory' => $this->maxVotesInMemory],
        );
    }

    protected function displayConfigurationSection(): void
    {
        $this->io->title('Configuration');

        $this->io->definitionList(
            ['Is vote weight allowed?' => $this->election->isVoteWeightAllowed() ? 'TRUE' : 'FALSE'],
            new TableSeparator,
            ['Votes are evaluated according to the implicit ranking rule?' => $this->election->getImplicitRankingRule() ? 'TRUE' : 'FALSE'],
            new TableSeparator,
            ['Is vote tie in rank allowed?' => \in_array(needle: NoTie::class, haystack: $this->election->getConstraints(), strict: true) ? 'FALSE' : 'TRUE']
        );
    }

    protected function displayDetailedElectionInputsSection(InputInterface $input, OutputInterface $output): void
    {
        $this->io->title('Detailed Election Inputs');

        if ($output->isVerbose()) {
            $this->displayVerbose($output);
        }

        // List-Votes Display Section
        if ($input->getOption('list-votes')) {
            $this->displayVotesCount($output);
            $this->io->newLine();
            $this->displayVotesList($output);
            $this->io->newLine();
        }
    }

    protected function displayVerbose(OutputInterface $output): void
    {
        $this->displayCandidatesList($output);
        $this->io->newLine();
        $this->displayVotesCount($output);
        $this->io->newLine();
    }

    protected function displayCandidatesList(OutputInterface $output): void
    {
        if (!$this->candidatesListIsWrite) {
            // Candidate List
            ($candidateTable = new Table($output))
                ->setHeaderTitle('Registered candidates')
                ->setHeaders(['Num', 'Candidate name'])

                ->setStyle($this->io->MainTableStyle)
                ->setColumnStyle(0, $this->io->FirstColumnStyle)
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

    protected function displayVotesCount(OutputInterface $output): void
    {
        if (!$this->votesCountIsWrite) {
            // Votes Count
            ($votesStatsTable = new Table($output))
                ->setHeaderTitle('Stats - votes registration')
                ->setHeaders(['Stats', 'Value'])
                ->setColumnStyle(0, (new TableStyle)->setPadType(\STR_PAD_LEFT))
                ->setStyle($this->io->MainTableStyle)
            ;

            $formatter = static fn(int $input): string => number_format($input, thousands_separator: ' ');

            $votesStatsTable->addRow(['Count registered votes', $formatter($this->election->countVotes())]);
            $votesStatsTable->addRow(['Count valid registered votes with constraints', $formatter($this->election->countValidVoteWithConstraints())]);
            $votesStatsTable->addRow(['Count invalid registered votes with constraints', $formatter($this->election->countInvalidVoteWithConstraints())]);
            $votesStatsTable->addRow(['Sum vote weight', $formatter($this->election->sumVotesWeight())]);
            $votesStatsTable->addRow(['Sum valid votes weight with constraints', $formatter($this->election->sumValidVotesWeightWithConstraints())]);

            $votesStatsTable->render();

            $this->votesCountIsWrite = true;
        }
    }

    protected function displayVotesList(OutputInterface $output): void
    {
        ($votesTable = new Table($output))
            ->setHeaderTitle('Registered Votes List')
            ->setHeaders(['Vote Num', 'Vote', 'Weight', 'Vote Tags'])

            ->setColumnWidth(0, 8)
            ->setColumnWidth(1, 30)
            ->setColumnMaxWidth(2, 6)
            ->setStyle($this->io->MainTableStyle)
        ;

        foreach ($this->election->getVotesValidUnderConstraintGenerator() as $voteKey => $oneVote) {
            $votesTable->addRow([($voteKey + 1), $oneVote->getSimpleRanking($this->election, false), $oneVote->getWeight($this->election), implode(';', $oneVote->tags)]);
        }

        $votesTable->render();
    }

    protected function displayPairwiseSection(OutputInterface $output): void
    {
        if (!$this->pairwiseIsWrite) {
            new Table($output)
                ->setHeaderTitle('Pairwise')
                ->setHeaders(['For each candidate, show their win, null, or lose'])
                ->setRows([[preg_replace('#!!float (\d+)#', '\1.0', Yaml::dump($this->election->getExplicitPairwise(), 100))]])

                ->setStyle($this->io->MainTableStyle)
                ->render()
            ;

            $this->pairwiseIsWrite = true;
        }
    }

    protected function displayNaturalCondorcet(InputInterface $input, OutputInterface $output): void
    {
        if ($input->getOption('natural-condorcet')) {
            $this->io->methodResultSection('Condorcet natural winner & loser');
            $this->io->newLine();

            new Table($output)
                ->setHeaderTitle('Natural Condorcet')
                ->setHeaders(['Type', 'Candidate'])
                ->setRows([
                    [CondorcetStyle::CONDORCET_WINNER_SYMBOL_FORMATED . '  Condorcet Winner', (string) ($this->election->getCondorcetWinner() ?? '-')],
                    [CondorcetStyle::CONDORCET_LOSER_SYMBOL_FORMATED . '  Condorcet Loser', (string) ($this->election->getCondorcetLoser() ?? '-')],
                ])

                ->setStyle($this->io->MainTableStyle)
                ->render()
            ;

            $this->io->newLine();
        }
    }

    protected function displayMethodsResultSection(InputInterface $input, OutputInterface $output): void
    {
        $methods = FormaterHelper::prepareMethods($input->getArgument('methods'));

        foreach ($methods as $oneMethod) {
            $this->io->methodResultSection($oneMethod['name']);

            if (isset($oneMethod['class']::$optionQuota) && $input->getOption('quota') !== null) {
                $this->election->setMethodOption($oneMethod['class'], 'Quota', StvQuotas::make($input->getOption('quota'))); // @phpstan-ignore-line
            }

            // Result
            $result = $this->election->getResult($oneMethod['name']);

            if (!empty($options = $result->methodOptions)) {
                $rows = [];
                foreach ($options as $key => $value) {
                    if ($value instanceof \BackedEnum) {
                        $value = $value->value;
                    } elseif (\is_array($value)) {
                        $value = implode(' / ', $value);
                    } elseif ($value instanceof Randomizer) {
                        $value = $value->engine::class;
                    }

                    $rows[] = [$key . ':', $value];
                }

                $this->io->newLine();

                new Table($output)
                    ->setHeaderTitle('Configuration: ' . $oneMethod['name'])
                    ->setHeaders(['Variable', 'Value'])
                    ->setRows($rows)

                    ->setColumnStyle(0, $this->io->FirstColumnStyle)
                    ->setColumnMaxWidth(0, 30)

                    ->setColumnMaxWidth(0, 40)

                    ->setStyle($this->io->MainTableStyle)
                    ->render()
                ;
            }

            $this->io->newLine();

            $this->io->write('<condor3>' . CondorcetStyle::CONDORCET_WINNER_SYMBOL_FORMATED . ' Condorcet Winner</>');
            $this->io->inlineSeparator();
            $this->io->writeln('<condor3>' . CondorcetStyle::CONDORCET_LOSER_SYMBOL_FORMATED . ' Condorcet Loser</>');

            new Table($output)
                ->setHeaderTitle('Results: ' . $oneMethod['name'])
                ->setHeaders(['Rank', 'Candidates'])
                ->setRows(FormaterHelper::formatResultTable($result))

                ->setColumnWidth(0, $fcw = 20)
                ->setColumnWidth(1, 50)
                ->setColumnMaxWidth(1, ($this->terminal->getWidth() - $fcw - 10 - 3))

                ->setColumnStyle(0, $this->io->FirstColumnStyle)
                ->setStyle($this->io->MainTableStyle)
                ->render()
            ;

            // Stats
            if ($this->displayMethodsStats) {
                $table = new Table($output)
                    ->setHeaderTitle('Stats: ' . $oneMethod['name'])

                    ->setColumnWidth(0, 73)
                    ->setColumnMaxWidth(0, $this->terminal->getWidth() - 10)
                    // ->setColumnStyle(0, $this->io->FirstColumnStyle)
                    ->setStyle($this->io->MainTableStyle)
                ;

                $line = 0;
                foreach ($result->stats as $oneStatKey => $oneStatEntry) {
                    if (++$line !== 1) {
                        $table->addRow(new TableSeparator);
                    }
                    $table->addRow([preg_replace('#!!float (\d+)#', '\1.0', Yaml::dump([$oneStatKey => $oneStatEntry], 100))]);
                }

                $table->render();
            }
        }
    }

    protected function displayTimerSection(): void
    {
        $executionTime = round($this->timer->getGlobalTimer(), 4);

        $this->io->newLine();
        $this->io->writeln('<comment>' . str_repeat('-', 23) . '</comment>', OutputInterface::VERBOSITY_VERBOSE);
        $this->io->writeln("<comment>Execution Time: <condor2>{$executionTime}s</condor2></comment>", OutputInterface::VERBOSITY_VERBOSE);
        $this->io->writeln('<comment>Ansi terminal color mode: <condor2>' . Terminal::getColorMode()->name . '</condor2></comment>', OutputInterface::VERBOSITY_DEBUG);
        $this->io->newLine();
    }

    # Processing

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
            if (!str_contains($e->getMessage(), 'class is already registered')) {
                throw $e;
            }
        }
    }

    protected function parseFromCandidatesArguments(): void
    {
        if ($file = CommandInputHelper::getFilePath($this->candidates)) {
            $this->election->parseCandidates($file, true);
        } else {
            $this->election->parseCandidates($this->candidates);
        }
    }

    protected function parseFromVotesArguments(?\Closure $callBack): void
    {
        // Parses Votes
        if ($file = CommandInputHelper::getFilePath($this->votes)) {
            $this->election->parseVotesWithoutFail(input: $file, isFile: true, callBack: $callBack);
        } else {
            $this->election->parseVotesWithoutFail(input: $this->votes, isFile: false, callBack: $callBack);
        }
    }

    protected function parseFromCondorcetElectionFormat(?\Closure $callBack): void
    {
        $file = CommandInputHelper::getFilePath($this->CondorcetElectionFormatPath);

        if ($file !== null) {
            new CondorcetElectionFormat($file)->setDataToAnElection($this->election, $callBack);
        } else {
            throw new FileDoesNotExistException('File does not exist, path: ' . $this->CondorcetElectionFormatPath);
        }
    }

    protected function parseFromDebianFormat(): void
    {
        $file = CommandInputHelper::getFilePath($this->DebianFormatPath);

        if ($file !== null) {
            new DebianFormat($file)->setDataToAnElection($this->election);
        } else {
            throw new FileDoesNotExistException('File does not exist, path: ' . $this->CondorcetElectionFormatPath);
        }
    }

    protected function parseFromDavidHillFormat(): void
    {
        $file = CommandInputHelper::getFilePath($this->DavidHillFormatPath);

        if ($file !== null) {
            new DavidHillFormat($file)->setDataToAnElection($this->election);
        } else {
            throw new FileDoesNotExistException('File does not exist, path: ' . $this->CondorcetElectionFormatPath);
        }
    }

    protected function useDataHandler(InputInterface $input): ?\Closure
    {
        if ($input->getOption('deactivate-file-cache') || !class_exists('\PDO') || !\in_array(needle: 'sqlite', haystack: \PDO::getAvailableDrivers(), strict: true)) {
            return null;
        } else {
            if ($this->iniMemoryLimit === '-1') {
                $memoryLimit = 8 * (1000 * 1048576); # Limit to 8GB, use a true memory limit to go further
            } else {
                $memoryLimit = ini_parse_quantity($this->iniMemoryLimit);
            }

            $memoryLimit = (int) ($memoryLimit / 1048576);
            $this->maxVotesInMemory = self::$VotesPerMB * $memoryLimit;

            return function (int $parsedVotesCounter): bool {
                if ($parsedVotesCounter > $this->maxVotesInMemory) {
                    /**
                     * @infection-ignore-all
                     */
                    if (file_exists($this->SQLitePath = getcwd() . \DIRECTORY_SEPARATOR . 'condorcet-bdd.sqlite')) {
                        unlink($this->SQLitePath);
                    }

                    /**
                     * @infection-ignore-all
                     */
                    $this->election->setExternalDataHandler(new PdoHandlerDriver(new \PDO('sqlite:' . $this->SQLitePath, '', '', [\PDO::ATTR_PERSISTENT => false]), true));

                    return false; // No, stop next iteration
                } else {
                    return true; // Yes, continue
                }
            };
        }
    }
}
