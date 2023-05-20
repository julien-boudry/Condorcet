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

    public function __construct(int|string|null $message = null)
    {
        // If there is a custom message, append it.
        if ($message !== null) {
            if (!empty($this->message)) {
                $this->message .= ': ' . $message;
            } else {
                $this->message = $message;
            }
        }

        parent::__construct($this->message);
    }
}
