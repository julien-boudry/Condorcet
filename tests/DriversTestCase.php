<?php declare(strict_types=1);

namespace Tests;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('pdo_sqlite')]
abstract class DriversTestCase extends CondorcetTestCase
{
    protected function hashVotesList(Election $elec): string
    {
        $c = 0;
        $voteCompil = '';
        foreach ($elec->getVotesManager() as $oneVote) {
            $c++;
            $voteCompil .= (string) $oneVote;
        }

        return $c . '||' . hash('md5', $voteCompil);
    }
}
