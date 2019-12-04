<?php
declare(strict_types=1);
namespace CondorcetPHP\Condorcet\DataManager;

use PHPUnit\Framework\TestCase;

use CondorcetPHP\Condorcet\DataManager\ArrayManager;

class ArrayManagerTest extends TestCase
{
    private ArrayManager $ArrayManager;

    protected function setUp() : void
    {
        $this->ArrayManager = new class extends ArrayManager {
            protected function preDeletedTask ($object) : void {}
            public function getDataContextObject () : DataContextInterface {}
        };
    }

    public function testOffsetSetAndOffetsetGet () : void
    {
        self::assertNull($this->ArrayManager->key());

        $this->ArrayManager[42] = 'foo';

        self::assertSame('foo', $this->ArrayManager[42]);

        self::assertNull($this->ArrayManager[43]);
    }
}
