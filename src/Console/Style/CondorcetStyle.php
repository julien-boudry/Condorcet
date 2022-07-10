<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Style;

use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CondorcetStyle extends SymfonyStyle
{
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

    public function instruction(string $prefix, string $message): void
    {
        $this->writeln("<options=bold;fg=red>{$prefix}:</> <fg=magenta>{$message}</>");
    }
}
