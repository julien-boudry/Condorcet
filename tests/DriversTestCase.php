<?php

declare(strict_types=1);

namespace Tests;

use CondorcetPHP\Condorcet\Election;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[RequiresPhpExtension('pdo_sqlite')]
class DriversTestCase extends TestCase
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
