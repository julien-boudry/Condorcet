<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Commands;

use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Result;
use CondorcetPHP\Condorcet\Constraints\NoTie;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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
    protected ?array $tags = null;
    protected bool $tagsWith = true;

    // TableFormat
    protected TableStyle $centerPadTypeStyle;

    protected function configure () : void
    {
        $this->setName('election')
            ->setDescription('Process an election')
            ->setHelp('This command takle candidates and votes in input. An output the résult of an election.')

            ->addOption(      'candidates','c'
                            , InputOption::VALUE_REQUIRED
                            , 'Candidates List file path or direct input'
            )
            ->addOption(      'votes','w'
                            , InputOption::VALUE_REQUIRED
                            , 'Votes List file path or direct input'
            )
            ->addOption(      'stats','s'
                            , InputOption::VALUE_NONE
                            , 'Get detailled stats'
            )
            ->addOption(      'list-votes','l'
                            , InputOption::VALUE_NONE
                            , 'List Registered Votes'
            )
            ->addOption(      'natural-condorcet','r'
                            , InputOption::VALUE_NONE
                            , 'Print natural Condorcet Winner / Loser'
            )
            ->addOption(      'desactivate-implicit-ranking','i'
                            , InputOption::VALUE_NONE
                            , 'Desactivate Implicit Ranking'
            )
            ->addOption(      'allows-votes-weight','g'
                            , InputOption::VALUE_NONE
                            , 'Allows vote weight'
            )
            ->addOption(      'no-tie','t'
                            , InputOption::VALUE_NONE
                            , 'Add no-tie constraint for vote'
            )

            ->addArgument(
                             'methods'
                            , InputArgument::OPTIONAL | InputArgument::IS_ARRAY
                            , 'Methods to ouput'
            )
        ;
    }

    protected function initialize (InputInterface $input, OutputInterface $output) : void
    {
        // Initialize Style
        $this->centerPadTypeStyle = (new TableStyle())->setPadType(STR_PAD_BOTH);

        // Setup Election Object
        $this->election = new Election;

            /// Implicit Ranking
            if ($input->getOption('desactivate-implicit-ranking')) :
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
    }

    protected function interact (InputInterface $input, OutputInterface $output) : void
    {
        // Interactive Candidates
        if (empty($candidates = $input->getOption('candidates'))) :
            $helper = $this->getHelper('question');

            $c = 0;
            $registeringCandidates = [];

            while (true) :
                $question = new Question('Please registering candidate N°'.++$c.' or press enter: ', null);
                $answer = $helper->ask($input, $output, $question);

                if ($answer === null) :
                    break;
                else :
                    $registeringCandidates[] = str_replace(';', ' ', $answer);
                endif;
            endwhile;

            $this->candidates = implode(';', $registeringCandidates);

        // Non-interactive candidates
        else :
            $this->candidates = $candidates;
        endif;

        // Interactive Votes
        if (empty($votes = $input->getOption('votes'))) :
            $helper = $this->getHelper('question');

            $c = 0;
            $registeringvotes = [];

            while (true) :
                $question = new Question('Please registering vote N°'.++$c.' or press enter: ', null);
                $answer = $helper->ask($input, $output, $question);

                if ($answer === null) :
                    break;
                else :
                    $registeringvotes[] = str_replace(';', ' ', $answer);
                endif;
            endwhile;

            $this->votes = implode(';', $registeringvotes);

        // Non-interactive votes
        else :
            $this->votes = $votes;
        endif;
    }

    protected function execute (InputInterface $input, OutputInterface $output) : void
    {
        $io = new SymfonyStyle ($input, $output);

        // Parse Votes & Candidates
        if ($file = $this->getFilePath($this->candidates)) :
            $this->election->parseCandidates($file, true);
        else :
            $this->election->parseCandidates($this->candidates);
        endif;

        if ($file = $this->getFilePath($this->votes)) :
            $this->election->parseVotes($file, true);
        else :
            $this->election->parseVotes($this->votes);
        endif;

        // Input Sum Up
        if ($output->isVerbose()) :
            $this->sectionSumUp($io ,$input,$output);
        endif;

        if ($input->getOption('list-votes')) :
            ($votesTable = new Table($output))
                ->setHeaderTitle('Registered Vote List')
                ->setHeaders(['Vote Num.', 'Vote', 'Vote Weight', 'Vote Tags'])
            ;

            foreach ($this->election->getVotesValidUnderConstraintGenerator() as $voteKey => $oneVote) :
                $votesTable->addRow( [ ($voteKey + 1), $oneVote->getSimpleRanking($this->election, false), $oneVote->getWeight($this->election), implode(',', $oneVote->getTags()) ] );
            endforeach;

            $votesTable->render();

            $io->newLine();
        endif;

        /// Natural Condorcet
        if ($input->getOption('natural-condorcet')) :
            $io->section('Condorcet Natural Winner & Loser');

            (new Table($output))
                ->setHeaderTitle('Natural Condorcet')
                ->setHeaders(['Type', 'Candidate'])
                ->setRows([
                            ['* Condorcet Winner', ( $this->election->getCondorcetWinner() ?? 'NULL' )],
                            ['# Condorcet Loser', ( $this->election->getCondorcetLoser() ?? 'NULL' )]
                ])

                ->render()
            ;

            $io->newLine();
        endif;


        // By Method

        $methods = $this->prepareMethods($input->getArgument('methods'));

        $io->section('Results per Methods');

        foreach ($methods as $oneMethod) :

            // Result
            $result = $this->election->getResult($oneMethod);

            (new Table($output))
                ->setHeaderTitle('Results: '.$oneMethod)
                ->setHeaders(['Rank', 'Candidates'])
                ->setRows($this->formatResultTable($result))

                ->setColumnStyle(0,$this->centerPadTypeStyle)
                ->setColumnWidth(0, 16)

                ->render()
            ;

            // Stats
            if ($input->getOption('stats')) :
                (new Table($output))
                    ->setHeaderTitle('Stats: '.$oneMethod)
                    ->setHeaders(['Stats'])
                    ->setRows([[preg_replace('#!!float (\d+)#', '\1.0', Yaml::dump($result->getStats(),100))]])

                    ->setColumnWidth(0, 40)
                    ->render()
                ;
            endif;

        endforeach;

        $io->newLine();
        $io->success('Success');
    }

    protected function sectionSumUp (SymfonyStyle $io, InputInterface $input, OutputInterface $output) : void
    {
        $io->section('Sum Up');

        // Votes Count
            ($votesStatsTable = new Table($output))
                ->setHeaderTitle('Election Configuration')
                ->setHeaders(['Param', 'Value'])
                ->setColumnStyle(0,(new Tablestyle())->setPadType(STR_PAD_LEFT))
            ;

            $votesStatsTable->addRow(['Is vote weight allowed?', $this->election->isVoteWeightAllowed() ? 'TRUE' : 'FALSE']);
            $votesStatsTable->addRow(['Votes are evaluated according to the implicit ranking rule?', $this->election->getImplicitRankingRule() ? 'TRUE' : 'FALSE']);
            $votesStatsTable->addRow(['Is vote tie in rank allowed?', in_array(NoTie::class, $this->election->getConstraints(), true) ? 'TRUE' : 'FALSE']);


            $votesStatsTable->render();

        // Candidate List
            ($candidateTable = new Table($output))
                ->setHeaderTitle('Registered Candidates')
                ->setHeaders(['Num', 'Candidate Name'])
                ->setStyle($this->centerPadTypeStyle)
                ->setColumnWidth(0, 14)
            ;

            $candidate_num = 1;
            foreach ($this->election->getCandidatesListAsString() as $oneCandidate) :
                $candidateTable->addRow([$candidate_num++, $oneCandidate]);
            endforeach;

            $candidateTable->render();

        // Votes Count
            ($votesStatsTable = new Table($output))
                ->setHeaderTitle('Stats - Votes Registration')
                ->setHeaders(['Stats', 'Value'])
                ->setColumnStyle(0,(new Tablestyle())->setPadType(STR_PAD_LEFT))
            ;

            $votesStatsTable->addRow(['Count Registered Votes', $this->election->countValidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Count Valid Registered Votes with constraints', $this->election->countValidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Count Invalid Registered Votes with constraints', $this->election->countInvalidVoteWithConstraints()]);
            $votesStatsTable->addRow(['Sum Vote Weight', $this->election->sumVotesWeight()]);
            $votesStatsTable->addRow(['Sum Valid Votes Weight with constraints', $this->election->sumValidVotesWeightWithConstraints()]);

            $votesStatsTable->render();

        $io->newLine();
    }

    protected function prepareMethods (array $methodArgument) : array
    {
        if (empty($methodArgument)) :
            return [Condorcet::getDefaultMethod()::METHOD_NAME[0]];
        else :
            $methods = [];

            foreach ($methodArgument as $oneMethod) :
                if (strtolower($oneMethod) === "all") :
                    $methods = Condorcet::getAuthMethods(false);
                    break;
                endif;

                if (Condorcet::isAuthMethod($oneMethod)) :
                    $method_name = Condorcet::getMethodClass($oneMethod)::METHOD_NAME[0];

                    if (!in_array($method_name, $methods, true)) :
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
            if (is_array($line)) :
                $line = implode(',', $line);
            endif;

            if ($rank === 1 && count($result[1]) === 1 && $result[1][0] === $result->getCondorcetWinner()) :
                $line = $line.'*';
            elseif ($rank === max(array_keys($resultArray)) && count($result[max(array_keys($resultArray))]) === 1 && $result[max(array_keys($resultArray))][0] === $result->getCondorcetLoser()) :
                $line = $line.'#';
            endif;

            $line = [$rank,$line];
        endforeach;


        $last_rank = max(array_keys($resultArray));

        return $resultArray;
    }

    protected function getFilePath (string $path)  : ?string
    {
        if ($this->isAbsolute($path) && \is_file($path)) :
            return $path;
        elseif (\is_file($file = getcwd().\DIRECTORY_SEPARATOR.$path)) :
            return $file;
        else:
            return null;
        endif;
        ;
    }

    protected function isAbsolute (string $path) : bool
    {
        return strspn($path, '/\\', 0, 1) || (\strlen($path) > 3 && ctype_alpha($path[0]) && ':' === $path[1] && strspn($path, '/\\', 2, 1));
    }

}