<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\Console\Commands;

use PHPUnit\Framework\TestCase;
use CondorcetPHP\Condorcet\Console\CondorcetApplication;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;


class ElectionCommandTest extends TestCase
{
    private readonly CommandTester $electionCommand;

    public function setUp(): void
    {
        CondorcetApplication::create();

        $this->electionCommand = new CommandTester(CondorcetApplication::$SymfonyConsoleApplication->find('election'));
    }

    public function testConsoleSimpleElection (): void
    {
        $this->electionCommand->execute([
                                            '--candidates' => 'A;B;C',
                                            '--votes' => 'A>B>C;C>B>A;B>A>C',
                                            '--stats' => null,
                                            '--natural-condorcet' => null,
                                            '--allows-votes-weight' => null,
                                            '--no-tie' => null,
                                            '--list-votes' => null,
                                            '--deactivate-implicit-ranking' => null,
                                            '--show-pairwise' => null
                                        ],[
                                            'verbosity' => OutputInterface::VERBOSITY_VERBOSE
                                        ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('3 candidates(s) registered  ||  3 vote(s) registered', $output);

        self::assertStringContainsString('Schulze', $output);
        self::assertStringContainsString('Registered candidates', $output);
        self::assertStringContainsString('Stats - votes registration', $output);
        self::assertStringContainsString('Registered votes list', $output);
        self::assertStringContainsString('Pairwise', $output);
        self::assertStringContainsString('Stats:', $output);

        self::assertMatchesRegularExpression('/Is vote weight allowed\?( )+TRUE/', $output);
        self::assertMatchesRegularExpression('/Votes are evaluated according to the implicit ranking rule\?( )+FALSE./', $output);
        self::assertMatchesRegularExpression('/Is vote tie in rank allowed\?( )+TRUE/', $output);

        self::assertStringContainsString('[OK] Success', $output);
    }

    public function testConsoleSeats (): void
    {
        $this->electionCommand->execute([
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
                                            'methods' => ['STV']
                                        ],[
                                            'verbosity' => OutputInterface::VERBOSITY_VERBOSE
                                        ]
        );

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('3 candidates(s) registered  ||  3 vote(s) registered', $output);

        self::assertStringContainsString('Seats:', $output);
        self::assertStringContainsString('42', $output);

        self::assertStringContainsString('[OK] Success', $output);
    }

    public function testQuotas (): void
    {
        $this->electionCommand->execute([
                                            '--candidates' => 'A;B;C',
                                            '--votes' => 'A>B>C;C>B>A;B>A>C',

                                            'methods' => ['STV'],
        ]);

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('Droop Quota', $output);

        $this->electionCommand->execute([
            '--candidates' => 'A;B;C',
            '--votes' => 'A>B>C;C>B>A;B>A>C',

            'methods' => ['STV'],
            '--quota' => 'imperiali'
        ]);

        $output = $this->electionCommand->getDisplay();

        self::assertStringContainsString('Imperiali', $output);
    }

    public function testConsoleAllMethodsArgument (): void
    {
        $this->electionCommand->execute([
                                            '--candidates' => 'A;B;C',
                                            '--votes' => 'A>B>C;C>B>A;B>A>C',

                                            'methods' => ['all']
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Copeland', $output);

        self::assertStringContainsString('[OK] Success', $output);
    }

    public function testConsoleMultiplesMethods (): void
    {
        $this->electionCommand->execute([
                                            '--candidates' => 'A;B;C',
                                            '--votes' => 'A>B>C;C>B>A;B>A>C',

                                            'methods' => ['Copeland', 'RankedPairs', 'Minimax']
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Copeland', $output);
        self::assertStringContainsString('Ranked Pairs M', $output);
        self::assertStringContainsString('Minimax Winning', $output);

        self::assertStringContainsString('[OK] Success', $output);
    }

    public function testConsoleFileInput (): void
    {
        $this->electionCommand->execute([
            '--candidates' => __DIR__.'/data.candidates',
            '--votes' => __DIR__.'/data.votes'
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Schulze', $output);
        self::assertStringContainsString('A,B', $output);
        self::assertStringContainsString('C#', $output);
    }

    public function testInteractiveCommand (): void
    {
        $this->electionCommand->setInputs([
            'A',
            'B',
            'C',
            '',
            'A>B>C',
            'B>A>C',
            'A>C>B',
            ''
            ]);

        $this->electionCommand->execute([
            'command' => 'election'
        ]);

        $output = $this->electionCommand->getDisplay();
        // \var_dump($output);

        self::assertStringContainsString('Results: Schulze Winning', $output);
    }

    public function testNonInteractionMode (): never
    {
        $this->expectException(\CondorcetPHP\Condorcet\Throwable\CondorcetException::class);
        $this->expectExceptionCode(6);

        $this->electionCommand->execute([],['interactive' => false]);

        // $output = $this->electionCommand->getDisplay();
        // \var_dump($output);
    }

    public function testCustomizeVotesPerMb (): void
    {
        $this->electionCommand->execute([
                                            '--candidates' => 'A;B;C',
                                            '--votes' => 'A>B>C;C>B>A;B>A>C',
                                            '--votes-per-mb' => 42
                                        ]);

        self::assertSame(42, \CondorcetPHP\Condorcet\Console\Commands\ElectionCommand::$VotesPerMB);

        // $output = $this->electionCommand->getDisplay();
        // \var_dump($output);
    }
}
