<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet;


use PHPUnit\Framework\TestCase;

class CondorcetExceptionTest extends TestCase
{
    public function testBuild ()
    {
        $ce = new CondorcetException(0,null);

        self::assertNotFalse(strpos((string) $ce, 'Mysterious Error'));
    }

}
