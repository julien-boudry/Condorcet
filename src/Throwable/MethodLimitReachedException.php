<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Throwable;

class MethodLimitReachedException extends CondorcetPublicApiException
{
    protected $message = "Method limit reached";
    public readonly string $method;

    public function __construct (string $method, ?string $message = null)
    {
        $this->method = $method;

        if ($message !== null) : 
            $this->message = $message;
        endif;
    }
}
