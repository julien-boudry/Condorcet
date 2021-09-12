<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Throwable;

use CondorcetPHP\Condorcet\CondorcetVersion;

// Custom Exception
abstract class CondorcetPublicApiException extends \Exception implements \Stringable
{
    use CondorcetVersion;

    public function __construct (string $message = '')
    {
        // If there is a custom message, append it.
        if (!empty($message)) :
            $this->message .= ': ' . $message;
        endif;
        parent::__construct($this->message);
    }

    public function __toString (): string
    {
        return static::class.": [{$this->code}]: {$this->message} (line: {$this->file}:{$this->line})\n";
    }
}
