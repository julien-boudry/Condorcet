<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Console\Commands;

use PHPUnit\Framework\TestCase;
use CondorcetPHP\Condorcet\Console\CondorcetApplication;
use CondorcetPHP\Condorcet\Throwable\Internal\{CondorcetInternalError, CondorcetInternalException};
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class ConverterCommandTest extends TestCase
{
    public const DEBIAN_INPUT_FILE = __DIR__.'/../../Tools/Converters/DebianData/leader2020_tally.txt';
    public const DAVIDHILL_INPUT_FILE = __DIR__.'/../../Tools/Converters/TidemanData/A77.HIL';

    public const OUTPUT_FILE = __DIR__.'/files/out.txt';

    private readonly CommandTester $converterCommand;

    protected function setUp(): void
    {
        CondorcetApplication::create();

        $this->converterCommand = new CommandTester(CondorcetApplication::$SymfonyConsoleApplication->find('convert'));
    }

    protected function tearDown(): void
    {
        file_exists(self::OUTPUT_FILE) && unlink(self::OUTPUT_FILE);
    }

    public static function conversionsProvider(): array
    {
        return [
            'fromDebianToCondorcet' => ['--from-debian-format', '--to-condorcet-election-format', self::DEBIAN_INPUT_FILE, __DIR__.'/files/fromDebianExpectedFile.cvotes'],
            'fromDavidHillToCondorcet' => ['--from-david-hill-format', '--to-condorcet-election-format', self::DAVIDHILL_INPUT_FILE, __DIR__.'/files/fromDavidHillExpectedFile.cvotes'],
        ];
    }

    #[DataProvider('conversionsProvider')]
    public function testSuccessfullConversions(string $from, string $to, string $input, string $comparaison): void
    {
        $this->converterCommand->execute(
            [
                $from => true,
                $to => true,
                'input' => $input,
                'output' => self::OUTPUT_FILE,
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->converterCommand->getDisplay();
        $this->converterCommand->assertCommandIsSuccessful();
        self::assertSame(0, $this->converterCommand->getStatusCode());
        self::assertEmpty($output);

        self::assertSame(file_get_contents($comparaison), file_get_contents(self::OUTPUT_FILE));
    }

    public function testLacksAnOption(): void
    {
        $this->expectException(CondorcetInternalException::class);
        $this->expectExceptionMessageMatches('/output/');

        $this->converterCommand->execute(
            [
                '--from-debian-format' => true,
                // Missing option
                'input' => self::DEBIAN_INPUT_FILE,
                'output' => self::OUTPUT_FILE,
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );
    }

    public function testLacksAnArgument(): void
    {
        $this->expectException(CondorcetInternalException::class);
        $this->expectExceptionMessageMatches('/output/');

        $this->converterCommand->execute(
            [
                '--from-debian-format' => true,
                '--to-condorcet-election-format' => true,
                'input' => self::DEBIAN_INPUT_FILE,
                // Missing
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );
    }

    public function testWrongInput(): void
    {
        $this->expectException(CondorcetInternalException::class);
        $this->expectExceptionMessage('The input file does not exist');

        $this->converterCommand->execute(
            [
                '--from-debian-format' => true,
                '--to-condorcet-election-format' => true,
                'input' => '42.txt',
                // Missing
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );
    }
}
