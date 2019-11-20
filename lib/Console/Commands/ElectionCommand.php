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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ElectionCommand extends Command
{
    protected Election $election;
    protected string $candidates;
    protected string $votes;

    protected bool $implicitRanking = false;
    
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
                            , 'Allows vote weigh'
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
        $this->election = new Election;

        /// Implicit Ranking
        if ($input->getOption('desactivate-implicit-ranking')) :
            $this->implicitRanking = true;
            $this->election->setImplicitRanking(false);
        endif;

        if ($input->getOption('allows-votes-weight')) :
            $this->votesWeight = true;
            $this->election->allowsVoteWeight(true);
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
            $output->writeln('Candidates: [ ' . implode(' , ', $election->getCandidatesList()) . ' ] --> ' . $election->countCandidates() . ' candidate(s)');
            $output->writeln('Votes: [ ' . $election->getVotesListAsString() . ' ] --> ' . $election->countVotes() . ' vote(s)');
        endif;


        /// Natural Condrocet
        if ($input->getOption('natural-condorcet')) :
            $output->writeln('Condorcet Natural Winner: ' . ( $this->election->getCondorcetWinner() ?? 'NULL' ) );
            $output->writeln('Condorcet Natural Loser: ' . ( $this->election->getCondorcetLoser() ?? 'NULL' ) );
        endif;


        // By Method

        $methods = $this->prepareMethods($input->getArgument('methods'));

        foreach ($methods as $oneMethod) :

            $result = $this->election->getResult();

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
                if ($oneMethod === "all") :
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