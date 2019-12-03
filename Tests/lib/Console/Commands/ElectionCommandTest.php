<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;

use PHPUnit\Framework\TestCase;
use CondorcetPHP\Condorcet\Console\CondorcetApplication;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;


class ElectionCommandTest extends TestCase
{
    private CommandTester $electionCommand;

    public function setUp() : void
    {
        CondorcetApplication::create();

        $this->electionCommand = new CommandTester(CondorcetApplication::$SymfonyConsoleApplication->find('election'));
    }

    public function testSimpleElection () : void
    {
        $this->electionCommand->execute([
                                            '--candidates' => 'A;B;C',
                                            '--votes' => 'A>B>C;C>B>A;B>A>C',
                                            '--stats' => null,
                                            '--natural-condorcet' => null,
                                            '--allows-votes-weight' => null,
                                            '--no-tie' => null
                                        ],[
                                            'verbosity' => OutputInterface::VERBOSITY_VERBOSE
                                        ]
        );

        $output = $this->electionCommand->getDisplay();
        // var_dump($output);

        self::assertStringContainsString('Schulze', $output);
        self::assertStringContainsString('Election Configuration', $output);
        self::assertStringContainsString('Registered Candidates', $output);
        self::assertStringContainsString('Stats - Votes Registration', $output);
        self::assertStringContainsString('Stats:', $output);

        self::assertStringContainsString('Is vote weight allowed? | TRUE', $output);
        self::assertStringContainsString('Votes are evaluated according to the implicit ranking rule? | TRUE ', $output);
        self::assertStringContainsString('Is vote tie in rank allowed? | TRUE', $output);

        self::assertStringContainsString('[OK] Success', $output);
    }
}
