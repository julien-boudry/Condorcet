<?php declare(strict_types=1);

use CondorcetPHP\Condorcet\Throwable\Internal\CondorcetInternalError;

test('message', function (): void {
    $this->expectException(CondorcetInternalError::class);
    $this->expectExceptionMessage($message = 'Test message');

    throw new CondorcetInternalError($message);
});
