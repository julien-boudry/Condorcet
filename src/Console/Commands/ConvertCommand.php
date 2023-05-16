<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Commands;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Console\Helper\CommandInputHelper;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use CondorcetPHP\Condorcet\Tools\Converters\{CondorcetElectionFormat, DavidHillFormat, DebianFormat};
use CondorcetPHP\Condorcet\Tools\Converters\Interface\{ConverterExport, ConverterImport};
use SplFileObject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'convert',
    description: 'Convert an election format input to another format as output',
    hidden: false,
)]
class ConvertCommand extends Command
{
    public static array $converters = [
        CondorcetElectionFormat::class,
        DebianFormat::class,
        DavidHillFormat::class,
    ];

    protected readonly string $fromConverter;
    protected readonly string $toConverter;

    protected readonly Election $election;

    protected string $input;
    protected SplFileObject $output;


    protected function configure(): void
    {
        foreach (self::$converters as $converter) {
            if (isset(class_implements($converter)[ConverterImport::class])) {
                $this->addOption(
                    name: 'from-'.$converter::COMMAND_LINE_OPTION_NAME,
                    mode: InputOption::VALUE_NONE,
                );
            }
        }

        foreach (self::$converters as $converter) {
            if (isset(class_implements($converter)[ConverterExport::class])) {
                $this->addOption(
                    name: 'to-'.$converter::COMMAND_LINE_OPTION_NAME,
                    mode: InputOption::VALUE_NONE,
                );
            }
        }

        $this
            ->addArgument(
                name: 'input',
                mode: InputArgument::REQUIRED,
                description: 'Input file',
            )
            ->addArgument(
                name: 'output',
                mode: InputArgument::REQUIRED,
                description: 'Output file',
            );

    }

    public function initialize(InputInterface $input, OutputInterface $output): void
    {
        // Get converters class

        $this->fromConverter = match (true) {
            $input->getOption('from-debian-format') => DebianFormat::class,
            $input->getOption('from-david-hill-format') => DavidHillFormat::class,
            $input->getOption('from-condorcet-election-format') => CondorcetElectionFormat::class,

            default => throw new CondorcetInternalException('The option defining the input format is missing')
        };

        $this->toConverter = match (true) {
            $input->getOption('to-condorcet-election-format') => CondorcetElectionFormat::class,
            // $input->getOption('to-civs-format') => CivsFormat::class,

            default => throw new CondorcetInternalException('The option defining the output format is missing')
        };

        // Get Files
        $this->input = $input->getArgument('input') ?? throw new CondorcetInternalException('Argument "input" is required');
        $this->input = CommandInputHelper::getFilePath($this->input) ?? throw new CondorcetInternalException('The input file does not exist');

        $output = CommandInputHelper::isAbsoluteAndExist($input->getArgument('output') ?? throw new CondorcetInternalException('Argument "output" is required')) ?
                        $input->getArgument('output') :
                        CommandInputHelper::getFilePath($input->getArgument('output'));

        $this->output = new SplFileObject($input->getArgument('output'), 'w+');
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->election = (new $this->fromConverter($this->input))->setDataToAnElection();

        $this->toConverter::createFromElection(election: $this->election, file: $this->output);

        return Command::SUCCESS;
    }
}
