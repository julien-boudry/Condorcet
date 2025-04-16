<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Console\Commands;

use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Console\Helper\CommandInputHelper;
use CondorcetPHP\Condorcet\Throwable\ConsoleInputException;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use CondorcetPHP\Condorcet\Tools\Converters\{CivsFormat, DavidHillFormat, DebianFormat};
use CondorcetPHP\Condorcet\Tools\Converters\CEF\CondorcetElectionFormat;
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
        'condorcet-election-format' => CondorcetElectionFormat::class,
        'debian-format' => DebianFormat::class,
        'david-hill-format' => DavidHillFormat::class,
        'civs-format' => CivsFormat::class,
    ];

    protected readonly string $fromConverter;
    protected readonly string $toConverter;

    protected readonly Election $election;

    protected string $input;
    protected ?SplFileObject $output;


    protected function configure(): void
    {
        foreach (self::$converters as $optionKey => $converter) {
            if (isset(class_implements($converter)[ConverterImport::class])) {
                $this->addOption(
                    name: 'from-' . $optionKey,
                    mode: InputOption::VALUE_NONE,
                );
            }
        }

        foreach (self::$converters as $optionKey => $converter) {
            if (isset(class_implements($converter)[ConverterExport::class])) {
                $this->addOption(
                    name: 'to-' . $optionKey,
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
        foreach (self::$converters as $optionKey => $converter) {
            if (empty($this->fromConverter) && $input->hasOption('from-' . $optionKey) && $input->getOption('from-' . $optionKey)) {
                $this->fromConverter = $converter;
            }

            if (empty($this->toConverter) && $input->hasOption('to-' . $optionKey) && $input->getOption('to-' . $optionKey)) {
                $this->toConverter = $converter;
            }
        }

        if (empty($this->fromConverter)) {
            throw new ConsoleInputException('The option defining the input format is missing');
        }
        if (empty($this->toConverter)) {
            throw new ConsoleInputException('The option defining the output format is missing');
        }

        // Get Files
        $this->input = $input->getArgument('input') ?? throw new CondorcetInternalException('Argument "input" is required');
        $this->input = CommandInputHelper::getFilePath($this->input) ?? throw new CondorcetInternalException('The input file does not exist');

        CommandInputHelper::isAbsoluteAndExist($input->getArgument('output') ?? throw new ConsoleInputException('Argument "output" is required')) ?
                        $input->getArgument('output') : CommandInputHelper::getFilePath($input->getArgument('output'));

        $this->output = new SplFileObject($input->getArgument('output'), 'w+');
    }


    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->election = new $this->fromConverter($this->input)->setDataToAnElection(); // @phpstan-ignore method.notFound

        $this->toConverter::createFromElection(election: $this->election, file: $this->output);

        $this->output = null; // Releases the file. Otherwise it can cause unexpected bugs when testing on some platforms (windows).

        return Command::SUCCESS;
    }
}
