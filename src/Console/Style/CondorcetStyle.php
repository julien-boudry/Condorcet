<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Style;

use CondorcetPHP\Condorcet\Console\CondorcetApplication;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CondorcetStyle extends SymfonyStyle
{
    public const CONDORCET_MAIN_COLOR = '#f57255';
    public const CONDORCET_SECONDARY_COLOR = '#8993c0';
    public const CONDORCET_THIRD_COLOR = '#e3e1e0';

    public const CONDORCET_WINNER_SYMBOL = '★';
    public const CONDORCET_LOSER_SYMBOL = '⚐';
    public const CONDORCET_WINNER_SYMBOL_FORMATED = '<fg=#ffff00>'.self::CONDORCET_WINNER_SYMBOL.'</>';
    public const CONDORCET_LOSER_SYMBOL_FORMATED = '<fg=#87ffff>'.self::CONDORCET_LOSER_SYMBOL.'</>';

    public readonly TableStyle $MainTableStyle;
    public readonly TableStyle $FirstColumnStyle;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        parent::__construct($input, $output);

        $output->getFormatter()->setStyle('condor1', new OutputFormatterStyle(foreground: self::CONDORCET_MAIN_COLOR));
        $output->getFormatter()->setStyle('condor2', new OutputFormatterStyle(foreground: self::CONDORCET_SECONDARY_COLOR));
        $output->getFormatter()->setStyle('condor3', new OutputFormatterStyle(foreground: self::CONDORCET_THIRD_COLOR));

        $output->getFormatter()->setStyle('condor1b', new OutputFormatterStyle(foreground: self::CONDORCET_MAIN_COLOR, options: ['bold']));
        $output->getFormatter()->setStyle('condor2b', new OutputFormatterStyle(foreground: self::CONDORCET_SECONDARY_COLOR, options: ['bold']));
        $output->getFormatter()->setStyle('condor3b', new OutputFormatterStyle(foreground: self::CONDORCET_THIRD_COLOR, options: ['bold']));

        $output->getFormatter()->setStyle('continue', new OutputFormatterStyle(foreground: '#008080'));
        $output->getFormatter()->setStyle('comment', new OutputFormatterStyle(foreground: '#fed5b7'));

        $this->MainTableStyle = (new TableStyle)
            ->setBorderFormat('<condor1>%s</>')
            ->setHeaderTitleFormat('<fg='.self::CONDORCET_THIRD_COLOR.';bg='.self::CONDORCET_SECONDARY_COLOR.';options=bold> %s </>')
            ->setCellHeaderFormat('<condor2>%s</>')
            ->setCellRowFormat('<condor3>%s</>')
        ;

        $this->FirstColumnStyle = (new TableStyle)
            ->setPadType(\STR_PAD_BOTH)
            // ->setCellRowFormat('<condor1>%s</>') # Buggy
        ;
    }

    public function logo(int $terminalSize): void
    {
        $path = __DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'Assets'.\DIRECTORY_SEPARATOR;

        if ($terminalSize >= 125) {
            $path .= 'logo.125c.ascii';
        } elseif ($terminalSize >= 90) {
            $path .= 'logo.90c.ascii';
        } else {
            $path .= 'logo.73c.ascii';
        }

        $this->writeln(file_get_contents($path));
    }

    public function author(string $author): void
    {
        $this->write("<condor1b>Author:</> <condor2>{$author}</>");
    }

    public function choiceMultiple(string $question, array $choices, mixed $default = null): mixed
    {
        if ($default !== null) {
            $values = array_flip($choices);
            $default = $values[$default] ?? $default;
        }

        $questionChoice = new ChoiceQuestion($question, $choices, $default);
        $questionChoice->setMultiselect(true);

        return $this->askQuestion($questionChoice);
    }

    public function homepage(string $homepage): void
    {
        $this->write("<condor1b>Homepage:</> <condor2><href={$homepage}>{$homepage}</></>");
    }

    public function inlineSeparator(): void
    {
        $this->write('<condor3> || </>');
    }

    public function instruction(string $prefix, string $message): void
    {
        $this->writeln("<condor1b>{$prefix}:</> <condor2>{$message}</>");
    }

    public function section(string $message): void
    {
        $this->block(
            messages: $message,
            type: null,
            style: 'fg='.self::CONDORCET_MAIN_COLOR.';bg='.self::CONDORCET_SECONDARY_COLOR.';options=bold',
            padding: false
        );
    }

    public function methodResultSection(string $message): void
    {
        $messageLength = mb_strlen($message) + 4;
        $prefixLength = 15;
        $totalLength = $messageLength + $prefixLength;
        $totalBorderLength = $totalLength + 4;

        $horizontalBorder = '<condor2>'.str_repeat('=', (int) $totalBorderLength).'</>';
        $vbs = '<condor2>|</>';

        $spaceMessage = '<bg='.self::CONDORCET_MAIN_COLOR.'>'.str_repeat(' ', $messageLength).'</>';
        $spacePrefix = '<bg='.self::CONDORCET_SECONDARY_COLOR.'>'.str_repeat(' ', $prefixLength).'</>';
        $bande = "{$vbs} ".$spacePrefix.$spaceMessage." {$vbs}";

        $this->writeln($horizontalBorder);
        $this->writeln($bande);
        $this->writeln("{$vbs} <bg=".self::CONDORCET_SECONDARY_COLOR.';options=bold>  Vote Method  </><bg='.self::CONDORCET_MAIN_COLOR.";options=bold>  {$message}  </> {$vbs}");
        $this->writeln($bande);
        $this->writeln($horizontalBorder);
        $this->newLine();
    }

    public function success(string|array $message): void
    {
        $this->block(
            messages: $message,
            type: 'OK',
            style: 'fg='.self::CONDORCET_MAIN_COLOR.';bg='.self::CONDORCET_SECONDARY_COLOR.';options=bold',
            padding: true
        );
    }

    public function version(): void
    {
        $version = CondorcetApplication::getVersionWithGitParsing();
        $this->write("<condor1b>Version:</> <condor2>{$version}</>");
    }
}
