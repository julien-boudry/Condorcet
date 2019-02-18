<?php
declare(strict_types=1);
namespace CondorcetPHP;


use PHPUnit\Framework\TestCase;

class CondorcetVersionTest extends TestCase
{
    public function testObjectVersion ()
    {
        $election = new Election;

        self::assertSame(CONDORCET::getVersion(),$election->getObjectVersion());
        self::assertSame(CONDORCET::getVersion('MAJOR'),$election->getObjectVersion('MAJOR'));
    }

}
