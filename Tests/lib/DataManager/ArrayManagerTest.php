<?php
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Tests\DataManager;

use CondorcetPHP\Condorcet\DataManager\ArrayManager;
use CondorcetPHP\Condorcet\Election;
use CondorcetPHP\Condorcet\Vote;
use PHPUnit\Framework\TestCase;

class ArrayManagerTest extends TestCase
{
    private ArrayManager $ArrayManager;

    protected function setUp(): void
    {
        $this->ArrayManager = new class extends ArrayManager {
            protected function preDeletedTask ($object): void {}
            protected function decodeOneEntity (string $data): Vote
            {
                $vote = new Vote ($data);
                $this->_Election->checkVoteCandidate($vote);
                $vote->registerLink($this->_Election);

                return $vote;
            }

            protected function encodeOneEntity (Vote $data): string
            {
                $data->destroyLink($this->_Election);

                return \str_replace([' > ',' = '],['>','='],(string) $data);
            }
        };
    }

    public function testOffsetSetAndOffetsetGet (): void
    {
        self::assertNull($this->ArrayManager->key());

        $this->ArrayManager[42] = 'foo';

        self::assertSame('foo', $this->ArrayManager[42]);

        self::assertNull($this->ArrayManager[43]);
    }
}
