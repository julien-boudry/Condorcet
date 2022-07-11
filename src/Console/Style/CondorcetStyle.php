<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console\Style;

use CondorcetPHP\Condorcet\Throwable\Internal\{CondorcetInternalError, NoGitShellException};
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class CondorcetStyle extends SymfonyStyle
{
    public const CONDORCET_MAIN_COLOR = '#f57255';
    public const CONDORCET_SECONDARY_COLOR = '#8993c0';

    public function author(string $author): void
    {
        $this->write('<options=bold;fg='.self::CONDORCET_MAIN_COLOR.'>Author:</> <fg='.self::CONDORCET_SECONDARY_COLOR.">{$author}</>");
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
        $this->write('<options=bold;fg='.self::CONDORCET_MAIN_COLOR.'>Homepage:</> <fg='.self::CONDORCET_SECONDARY_COLOR."><href={$homepage}>{$homepage}</></>");
    }

    public function instruction(string $prefix, string $message): void
    {
        $this->writeln('<options=bold;fg='.self::CONDORCET_MAIN_COLOR.">{$prefix}:</> <fg=".self::CONDORCET_SECONDARY_COLOR.">{$message}</>");
    }

    public function logo(string $path): void
    {
        $logo = file_get_contents($path);
        $logo = str_replace(['CondorcetMainColor', 'CondorcetSecondaryColor'], [self::CONDORCET_MAIN_COLOR, self::CONDORCET_SECONDARY_COLOR], $logo);

        $this->writeln($logo);
    }

    public function version(string $applicationOfficialVersion): void
    {
        $git = static function (string $path): string {
            if (!is_dir($path . \DIRECTORY_SEPARATOR . '.git')) {
                throw new CondorcetInternalError('Path is not valid');
            }

            $process = proc_open(
                'git describe --tags --match="v[0-9]*\.[0-9]*\.[0-9]*"',
                [
                    1 => ['pipe', 'w'],
                    2 => ['pipe', 'w'],
                ],
                $pipes,
                $path
            );

            if (!\is_resource($process)) {
                throw new NoGitShellException;
            }

            $result = trim(stream_get_contents($pipes[1]));

            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                throw new NoGitShellException;
            }

            return $result;
        };

        try {
            $version = $git(__DIR__.'/../../../');
            $commit = explode('-', $version)[2];

            $match = [];
            preg_match('/^v([0-9]+\.[0-9]+\.[0-9]+)/', $version, $match);
            $gitLastestTag = $match[1];

            $version = (version_compare($gitLastestTag, $applicationOfficialVersion, '>=')) ? $version : $applicationOfficialVersion.'-(dev)-'.$commit;
        } catch (NoGitShellException) { // Git no available, use the Condorcet Version
            $version = $applicationOfficialVersion;
        }

        $this->write('<options=bold;fg='.self::CONDORCET_MAIN_COLOR.'>Version:</> <fg='.self::CONDORCET_SECONDARY_COLOR.">{$version}</>");
    }
}
