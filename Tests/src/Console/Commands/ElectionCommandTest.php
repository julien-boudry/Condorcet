<?php

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Console\Commands;

use CondorcetPHP\Condorcet\Console\Commands\ElectionCommand;
use CondorcetPHP\Condorcet\Throwable\{CandidateExistsException, ResultRequestedWithoutVotesException};
use PHPUnit\Framework\TestCase;
use CondorcetPHP\Condorcet\Console\CondorcetApplication;
use CondorcetPHP\Condorcet\Console\Style\CondorcetStyle;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class ElectionCommandTest extends TestCase
{
    private readonly CommandTester $electionCommand;

    protected function setUp(): void
    {
        CondorcetApplication::create();

        $this->electionCommand = new CommandTester(CondorcetApplication::$SymfonyConsoleApplication->find('election'));
    }

    public function testConsoleSimpleElection(): void
    {
        $this->electionCommand->execute(
            [
                '--candidates' => 'A;B;C',
                '--votes' => 'A>B>C;C>B>A;B>A>C',
                '--stats' => null,
                '--natural-condorcet' => null,
                '--allows-votes-weight' => null,
                '--no-tie' => null,
                '--list-votes' => null,
                '--deactivate-implicit-ranking' => null,
                '--show-pairwise' => null,
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('3 candidates registered || 3 votes registered', $output);

        self::assertStringContainsString('Schulze', $output);
        self::assertStringContainsString('Registered candidates', $output);
        self::assertStringContainsString('Stats - votes registration', $output);
        self::assertStringContainsString('Registered Votes List', $output);
        self::assertStringContainsString('Pairwise', $output);
        self::assertStringContainsString('Stats:', $output);

        self::assertMatchesRegularExpression('/Is vote weight allowed\?( )+TRUE/', $output);
        self::assertMatchesRegularExpression('/Votes are evaluated according to the implicit ranking rule\?( )+FALSE./', $output);
        self::assertMatchesRegularExpression('/Is vote tie in rank allowed\?( )+FALSE/', $output);
    }

    public function testConsoleSeats(): void
    {
        $this->electionCommand->execute(
            [
                '--candidates' => 'A;B;C',
                '--votes' => 'A>B>C;C>B>A;B>A>C',
                '--stats' => null,
                '--natural-condorcet' => null,
                '--allows-votes-weight' => null,
                '--no-tie' => null,
                '--list-votes' => null,
                '--deactivate-implicit-ranking' => null,
                '--show-pairwise' => null,

                '--seats' => 42,
                'methods' => ['STV'],
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('3 candidates registered || 3 votes registered', $output);

        self::assertStringContainsString('Seats:', $output);
        self::assertStringContainsString('42', $output);
    }

    public function testQuotas(): void
    {
        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes' => 'A>B>C;C>B>A;B>A>C',

            'methods' => ['STV'],
        ]);

        $output = $this->electionCommand->getDisplay();

        self::assertMatchesRegularExpression('/Is vote tie in rank allowed\?( )+TRUE/', $output);
        self::assertStringContainsString('Droop Quota', $output);

        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes' => 'A>B>C;C>B>A;B>A>C',

            'methods' => ['STV'],
            '--quota' => 'imperiali',
        ]);

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('Imperiali', $output);
    }

    public function testConsoleAllMethodsArgument(): void
    {
        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes' => 'A>B>C;C>B>A;B>A>C',

            'methods' => ['all'],
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Copeland', $output);
    }

    public function testConsoleMultiplesMethods(): void
    {
        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes' => 'A>B>C;C>B>A;B>A>C',

            'methods' => ['Copeland', 'RankedPairs', 'Minimax'],
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Copeland', $output);
        self::assertStringContainsString('Ranked Pairs M', $output);
        self::assertStringContainsString('Minimax Winning', $output);
    }

    public function testConsoleFileInput(): void
    {
        $this->electionCommand->execute([
            '--candidates' => __DIR__.'/data.candidates',
            '--votes' => __DIR__.'/data.votes',
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Schulze', $output);
        self::assertStringContainsString('A,B', $output);
        self::assertStringContainsString('C '.CondorcetStyle::CONDORCET_LOSER_SYMBOL, $output);
    }

    public function testInteractiveCommand(): void
    {
        $this->electionCommand->setInputs([
            'A',
            'B',
            'C',
            '',
            'A>B>C',
            'B>A>C',
            'A>C>B',
            '',
        ]);

        $this->electionCommand->execute([
            'command' => 'election',
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Results: Schulze Winning', $output);
    }

    public function testNonInteractionMode(): never
    {
        $this->expectException(ResultRequestedWithoutVotesException::class);
        $this->expectExceptionMessage('The result cannot be requested without votes');

        $this->electionCommand->execute([], ['interactive' => false]);

        // $output = $this->electionCommand->getDisplay();
        // \var_dump($output);
    }

    public function testCustomizeVotesPerMb(): void
    {
        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes' => 'A>B>C;C>B>A;B>A>C',
            '--votes-per-mb' => 42,
        ]);

        self::assertSame(42, \CondorcetPHP\Condorcet\Console\Commands\ElectionCommand::$VotesPerMB);

        // $output = $this->electionCommand->getDisplay();
        // \var_dump($output);
    }

    public function testVoteWithDb1(): void
    {
        ElectionCommand::$forceIniMemoryLimitTo = '128M';

        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes-per-mb' => 1,
            '--votes' => 'A>B>C * '.(((int) preg_replace('`[^0-9]`', '', ElectionCommand::$forceIniMemoryLimitTo)) + 1), # Must be superior to memory limit in MB
        ], [
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
        ]);

        $output = $this->electionCommand->getDisplay();

        self::assertMatchesRegularExpression('/Votes per Mb +1/', $output);
        self::assertMatchesRegularExpression('/Db is used +yes, using path\\:/', $output);

        ElectionCommand::$forceIniMemoryLimitTo = null;

        # And absence of this error: unlink(path): Resource temporarily unavailable
    }


    public function testNaturalCondorcet(): void
    {
        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes' => 'A=B=C',
            '--natural-condorcet' => true,
        ]);

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString(CondorcetStyle::CONDORCET_WINNER_SYMBOL.'  Condorcet Winner | -', $output);
        self::assertStringContainsString(CondorcetStyle::CONDORCET_LOSER_SYMBOL.'  Condorcet Loser  | -', $output);
    }

    public function testFromCondorcetElectionFormat_DoubleCandidates(): void
    {
        $this->expectException(CandidateExistsException::class);

        $this->electionCommand->execute(
            [
                '--candidates' => 'A;B;C',
                '--import-condorcet-election-format' => __DIR__.'/../../Tools/Converters/CondorcetElectionFormatData/test1.cvotes',
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );
    }

    public function testFromCondorcetElectionFormat_ArgumentpriorityAndDoubleVoteArgument(): void
    {
        $this->electionCommand->execute(
            [
                '--import-condorcet-election-format' => __DIR__.'/../../Tools/Converters/CondorcetElectionFormatData/test1.cvotes',
                '--votes' => 'C>A',
                '--deactivate-implicit-ranking' => null,
                '--no-tie' => null,
                '--allows-votes-weight' => null,
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('3 candidates registered || 2 votes registered', $output);

        self::assertStringContainsString('Schulze', $output);
        self::assertStringContainsString('Registered candidates', $output);
        self::assertStringContainsString('Stats - votes registration', $output);

        self::assertMatchesRegularExpression('/Is vote weight allowed\?( )+TRUE/', $output);
        self::assertMatchesRegularExpression('/Votes are evaluated according to the implicit ranking rule\?( )+FALSE./', $output);
        self::assertMatchesRegularExpression('/Is vote tie in rank allowed\?( )+FALSE/', $output);

        self::assertStringContainsString('Sum vote weight | 3', $output);
    }

    public function testFromCondorcetElectionFormat_Arguments(): void
    {
        $this->electionCommand->execute(
            [
                '--import-condorcet-election-format' => __DIR__.'/../../Tools/Converters/CondorcetElectionFormatData/test2.cvotes',
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('3 candidates registered || 2 votes registered', $output);

        self::assertStringContainsString('Schulze', $output);
        self::assertStringContainsString('Registered candidates', $output);
        self::assertStringContainsString('Stats - votes registration', $output);

        self::assertMatchesRegularExpression('/Is vote weight allowed\?( )+FALSE/', $output);
        self::assertMatchesRegularExpression('/Votes are evaluated according to the implicit ranking rule\?( )+FALSE./', $output);
        self::assertMatchesRegularExpression('/Is vote tie in rank allowed\?( )+TRUE/', $output);

        self::assertStringContainsString('Sum vote weight | 2', $output);

        self::assertStringContainsString('B '.CondorcetStyle::CONDORCET_WINNER_SYMBOL, $output); # Condorcet Winner
    }

    public function testVoteWithDb_CondorcetElectionFormat(): void
    {
        ElectionCommand::$forceIniMemoryLimitTo = '128M';

        $this->electionCommand->execute([
            '--votes-per-mb' => 1,
            '--import-condorcet-election-format' => __DIR__.'/../../Tools/Converters/CondorcetElectionFormatData/test3.cvotes',
        ], [
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
        ]);

        $output = $this->electionCommand->getDisplay();

        self::assertMatchesRegularExpression('/Votes per Mb +1/', $output);
        self::assertStringContainsString('Db is used', $output);
        self::assertStringContainsString('yes, using path:', $output);

        ElectionCommand::$forceIniMemoryLimitTo = null;

        # And absence of this error: unlink(path): Resource temporarily unavailable
    }

    public function testFromDebianFormat(): void
    {
        $this->electionCommand->execute(
            [
                '--import-debian-format' => __DIR__.'/../../Tools/Converters/DebianData/leader2020_tally.txt',
                'methods' => ['STV'],
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('4 candidates registered || 339 votes registered', $output);

        self::assertStringContainsString('STV', $output);
        self::assertStringContainsString('Registered candidates', $output);
        self::assertStringContainsString('Stats - votes registration', $output);

        self::assertMatchesRegularExpression('/Is vote weight allowed\?( )+FALSE/', $output);
        self::assertMatchesRegularExpression('/Votes are evaluated according to the implicit ranking rule\?( )+TRUE./', $output);
        self::assertMatchesRegularExpression('/Is vote tie in rank allowed\?( )+TRUE/', $output);

        self::assertStringContainsString('Sum vote weight | 339', $output);

        self::assertStringContainsString('Jonathan Carter '.CondorcetStyle::CONDORCET_WINNER_SYMBOL, $output); # Condorcet Winner
        self::assertMatchesRegularExpression('/Seats: *\| 1/', $output);
    }

    public function testFromDavidHillFormat(): void
    {
        $this->electionCommand->execute(
            [
                '--import-david-hill-format' => __DIR__.'/../../Tools/Converters/TidemanData/A1.HIL',
                'methods' => ['STV'],
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('10 candidates registered || 380 votes registered', $output);

        self::assertStringContainsString('STV', $output);
        self::assertStringContainsString('Registered candidates', $output);
        self::assertStringContainsString('Stats - votes registration', $output);

        self::assertMatchesRegularExpression('/Is vote weight allowed\?( )+FALSE/', $output);
        self::assertMatchesRegularExpression('/Votes are evaluated according to the implicit ranking rule\?( )+TRUE./', $output);
        self::assertMatchesRegularExpression('/Is vote tie in rank allowed\?( )+TRUE/', $output);

        self::assertStringContainsString('Sum vote weight | 380', $output);

        self::assertStringContainsString('Candidate  1 '.CondorcetStyle::CONDORCET_WINNER_SYMBOL, $output); # Condorcet Winner
        self::assertMatchesRegularExpression('/Seats: *\| 3/', $output);
    }

    // Issue #110
    public function testFileCacheForbidden(): void
    {
        $this->electionCommand->execute(
            [
                '--candidates' => 'A;B;C',
                '--votes' => 'A*10000',
                '--votes-per-mb' => 1,
                '--deactivate-file-cache' => null,
            ],
            [
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE,
            ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('10 000 votes registered', $output);
    }
}
