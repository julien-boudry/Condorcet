<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Console;

use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use CondorcetPHP\Condorcet\Condorcet;
use CondorcetPHP\Condorcet\Console\Commands\ElectionCommand;
use CondorcetPHP\Condorcet\Throwable\Internal\NoGitShellException;
use Symfony\Component\Console\Output\AnsiColorMode;
use Symfony\Component\Console\Terminal;

abstract class CondorcetApplication
{
    public static SymfonyConsoleApplication $SymfonyConsoleApplication;

    /**
     * @infection-ignore-all
     */
    public static function run(): void
    {
        // Run
        self::create() && self::$SymfonyConsoleApplication->run();
    }

    public static function create(): true
    {
        // New App
        self::$SymfonyConsoleApplication = new SymfonyConsoleApplication('Condorcet', Condorcet::getVersion());

        // Election command
        $command = new ElectionCommand;
        self::$SymfonyConsoleApplication->add($command);
        self::$SymfonyConsoleApplication->setDefaultCommand($command->getName(), false);

        // Force True color for Docker (or others), based on env
        getenv('CONDORCET_TERM_ANSI24') && Terminal::setColorMode(AnsiColorMode::Ansi24);

        return true;
    }

    public static function getVersionWithGitParsing(): string
    {
        $git = static function (string $path): string {
            if (!is_dir($path . \DIRECTORY_SEPARATOR . '.git')) {
                throw new NoGitShellException('Path is not valid');
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

        $applicationOfficialVersion = Condorcet::getVersion();

        try {
            $version = $git(__DIR__.'/../../');
            $commit = explode('-', $version)[2] ?? null;

            $match = [];
            preg_match('/^v([0-9]+\.[0-9]+\.[0-9]+)/', $version, $match);
            $gitLastestTag = $match[1];

            $version = (version_compare($gitLastestTag, $applicationOfficialVersion, '>=')) ? $version : $applicationOfficialVersion.'-(dev)-'.$commit;
        } catch (NoGitShellException) { // Git no available, use the Condorcet Version
            $version = $applicationOfficialVersion;
        }

        return $version;
    }
}
