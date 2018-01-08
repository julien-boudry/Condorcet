<?php
declare(strict_types=1);
namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetException;
use Condorcet\Election;
use Condorcet\Algo\Method;
use Condorcet\Algo\MethodInterface;

use PHPUnit\Framework\TestCase;

class CondorcetExceptionTest extends TestCase
{
    public function testBuild ()
    {
        $ce = new CondorcetException(0,null);

        self::assertNotFalse(strpos((string) $ce, 'Mysterious Error'));
    }

}
