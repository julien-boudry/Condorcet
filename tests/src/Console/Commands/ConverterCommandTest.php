<?php

declare(strict_types=1);
use CondorcetPHP\Condorcet\Console\CondorcetApplication;
use CondorcetPHP\Condorcet\Throwable\ConsoleInputException;
use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\src\Console\ConsoleTestCase;

beforeEach(function (): void {
    CondorcetApplication::create();

    $this->converterCommand = new CommandTester(CondorcetApplication::$SymfonyConsoleApplication->find('convert'));
});

afterEach(function (): void {
    file_exists(self::OUTPUT_FILE) && unlink(self::OUTPUT_FILE);
});

dataset('conversionsProvider', fn() => [
    'fromDebianToCondorcet' => ['--from-debian-format', '--to-condorcet-election-format', ConsoleTestCase::DEBIAN_INPUT_FILE, __DIR__ . '/files/fromDebianExpectedFile.cvotes'],
    'fromDavidHillToCondorcet' => ['--from-david-hill-format', '--to-condorcet-election-format', ConsoleTestCase::DAVIDHILL_INPUT_FILE, __DIR__ . '/files/fromDavidHillExpectedFile.cvotes'],
]);

test('successfull conversions', function (string $from, string $to, string $input, string $comparaison): void {
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
    expect($this->converterCommand->getStatusCode())->toBe(0);
    expect($output)->toBeEmpty();

    expect(file_get_contents(self::OUTPUT_FILE))->toBe(file_get_contents($comparaison));
})->with('conversionsProvider');

test('lacks an option', function (): void {
    $this->expectException(ConsoleInputException::class);
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
});

test('lacks an argument', function (): void {
    $this->expectException(ConsoleInputException::class);
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
});

test('wrong input', function (): void {
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
});
