<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;

class CondorcetVersionTest extends TestCase
{
    public function testObjectVersion ()
    {
        $election = new Election;

        self::assertSame(CONDORCET::getVersion(),$election->getObjectVersion());
        self::assertSame(CONDORCET::getVersion(true),$election->getObjectVersion(true));
    }

}
