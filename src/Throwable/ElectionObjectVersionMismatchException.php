<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Throwable;

use CondorcetPHP\Condorcet\Condorcet;

class ElectionObjectVersionMismatchException extends CondorcetPublicApiException
{
    protected $message = 'Version mismatch';

    public function __construct(string $message = '')
    {
        parent::__construct(
            "The election object has version '{$message}' " .
            'which is different from the current class ' .
            "version '" . Condorcet::getVersion(true) . "'"
        );
    }
}
