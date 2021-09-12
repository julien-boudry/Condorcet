<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, PublicAPI, Related};
use CondorcetPHP\Condorcet\{CondorcetVersion, Election, Result};
use CondorcetPHP\Condorcet\Throwable\CondorcetException;

// Generic for Algorithms
abstract class Method
{
    use CondorcetVersion;

    public const IS_PROPORTIONAL = false;

    public static ?int $MaxCandidates = null;

    protected Election $_selfElection;
    protected ?Result $_Result = null;

    // Static

    final public static function setOption (string $optionName, mixed $optionValue): bool
    {
        $optionVar = 'option'.ucfirst($optionName);

        static::$$optionVar = $optionValue;

        return true;
    }

    //

    public function __construct (Election $mother)
    {
        $this->_selfElection = $mother;

        if (!\is_null(static::$MaxCandidates) && $this->_selfElection->countCandidates() > static::$MaxCandidates) :
            throw new CondorcetException(101, static::METHOD_NAME[0].' is configured to accept only '.static::$MaxCandidates.' candidates');
        endif;
    }

    public function __destruct ()
    {
        unset($this->_selfElection, $this->_Result);
    }

    public function getResult (): Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

        $this->compute();

        return $this->_Result;
    }

    abstract protected function getStats (): array;

    protected function createResult (array $result): Result
    {
    	$optionsList = \array_keys((new \ReflectionClass(static::class))->getStaticProperties());
        $optionsList = \array_filter($optionsList, function (string $name): bool {
            return \str_starts_with($name ,'option');
        });

        $methodOptions = [];

        foreach ($optionsList as $oneOption) :
            $methodOptions[substr($oneOption,6)] = static::$$oneOption;
        endforeach;

        return new Result (
            fromMethod: static::METHOD_NAME[0],
            byClass: $this::class,
    		election: $this->_selfElection,
    		result: $result,
            stats: $this->getStats(),
            seats: (static::IS_PROPORTIONAL) ? $this->_selfElection->getNumberOfSeats() : null,
            methodOptions: $methodOptions
    	);
    }
}
