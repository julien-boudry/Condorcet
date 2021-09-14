<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Throwable;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\CondorcetVersion;

// Custom Exception
class CondorcetException extends \Exception implements \Stringable
{
    use CondorcetVersion;

    public const CODE_RANGE = [0,1000];

    public const EXCEPTION_CODE = [
        // DataManager
        50 => 'This entity does not exist.',
    ];

    protected array $_infos;

    public function __construct (int $code = 0, string ...$infos)
    {
        if ($code < static::CODE_RANGE[0] || $code > static::CODE_RANGE[1]) :
            throw new self (0,'Exception class error');
        endif;

        $this->_infos = $infos;

        parent::__construct(message: $this->correspondence($code), code: $code);
    }

    public function __toString (): string
    {
           return static::class . ": [{$this->code}]: {$this->message} (line: {$this->file}:{$this->line})\n";
    }

    protected function correspondence (int $code): string
    {
        // Algorithms
        if ($code === 0 || $code === 101) :
            return $this->_infos[0] ?? '';
        endif;

        if ( \array_key_exists($code, static::EXCEPTION_CODE) ) :
            return \str_replace('{{ infos1 }}', $this->_infos[0] ?? '', static::EXCEPTION_CODE[$code]);
        else :
            return static::EXCEPTION_CODE[0] ?? 'Mysterious Error';
        endif;
    }
}
