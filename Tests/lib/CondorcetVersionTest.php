<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;

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
