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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;


class ElectionCommand extends Command
{
    protected function configure () : void
    {
        $this->setName('election')
            ->setDescription('Process an election')
            ->setHelp('This command takle candidates and votes in input. An output the résult of an election.')

            ->addOption(      'candidates','c'
                            , InputOption::VALUE_REQUIRED
                            , 'Candidates List file path or direct input'
            )


            ->addOption(      'votes','i'
                            , InputOption::VALUE_REQUIRED
                            , 'Votes List file path or direct input'
            )


            ->addOption(      'stats','s'
                            , InputOption::VALUE_NONE
                            , 'Get detailled stats'
            )

            ->addOption(      'natural-condorcet','r'
                            , InputOption::VALUE_NONE
                            , 'Print natual Condorcet Winner / Loser'
            )

            ->addArgument(
                             'methods'
                            , InputArgument::OPTIONAL | InputArgument::IS_ARRAY
                            , 'Methods to ouput'
            )
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output) : void
    {
        $election = new Election;

        $election->parseCandidates($input->getOption('candidates'));
        $election->parseVotes($input->getOption('votes'));

        // Input Sum Up
        if ($output->isVerbose()) :
            $output->writeln('Candidates: [ ' . implode(' , ', $election->getCandidatesList()) . ' ] --> ' . $election->countCandidates() . ' candidate(s)');
            $output->writeln('Votes: [ ' . $election->getVotesListAsString() . ' ] --> ' . $election->countVotes() . ' vote(s)');
        endif;


        /// Natrual Condrocet
        if ($input->getOption('natural-condorcet')) :
            $output->writeln('Condorcet Natural Winner: ' . ( $election->getCondorcetWinner() ?? 'NULL' ) );
            $output->writeln('Condorcet Natural Loser: ' . ( $election->getCondorcetLoser() ?? 'NULL' ) );
        endif;


        // By Method

        $methods = $this->prepareMethods($input->getArgument('methods'));

        foreach ($methods as $oneMethod) :

            $result = $election->getResult();

            $table = new Table($output);
            
            $table
                ->setHeaderTitle($oneMethod)
                ->setHeaders(['Rank', 'Candidates'])
                ->setRows($this->formatResultTable($result))

                ->setColumnWidth(0, 12)
            ;

            $table->render();

        endforeach;
    }

    protected function prepareMethods (array $methodArgument) : array
    {
        if (empty($methodArgument)) :
            return [Condorcet::getDefaultMethod()::METHOD_NAME[0]];
        else :
            $methods = [];

            foreach ($methodArgument as $oneMethod) :
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

}