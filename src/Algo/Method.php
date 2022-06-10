<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Description, Example, FunctionReturn, InternalModulesAPI, PublicAPI, Related, Throws};
use CondorcetPHP\Condorcet\{CondorcetVersion, Election, Result};
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;

// Generic for Algorithms
abstract class Method
{
    use CondorcetVersion;

    public const IS_PROPORTIONAL = false;

    public static ?int $MaxCandidates = null;

    protected readonly \WeakReference $_selfElection;
    protected ?Result $_Result = null;

    // Internal precision
    public const DECIMAL_PRECISION = 9;

    // Static

    final public static function setOption (string $optionName, array|\BackedEnum|int|string $optionValue): bool
    {
        $optionVar = 'option'.ucfirst($optionName);

        static::$$optionVar = $optionValue;

        return true;
    }

    //

    #[Throws(CandidatesMaxNumberReachedException::class)]
    public function __construct (Election $mother)
    {
        $this->setElection($mother);

        if (!\is_null(static::$MaxCandidates) && $mother->countCandidates() > static::$MaxCandidates) :
            throw new CandidatesMaxNumberReachedException(static::METHOD_NAME[0], static::$MaxCandidates);
        endif;
    }

    public function __serialize (): array
    {
        $r = \get_object_vars($this);
        unset($r['_selfElection']);

        return $r;
    }

    public function setElection (Election $election): void
    {
        $this->_selfElection = \WeakReference::create($election);
    }

    #[InternalModulesAPI]
    public function getElection (): Election
    {
        return $this->_selfElection->get();
    }

    #[InternalModulesAPI]
    public function getResult (): Result
    {
        // Cache
        if ( $this->_Result !== null ) :
            return $this->_Result;
        endif;

        $this->compute();

        return $this->_Result;
    }

    protected function compute (): void {}

    abstract protected function getStats (): array;

    protected function createResult (array $result): Result
    {
    	$optionsList = \array_keys((new \ReflectionClass(static::class))->getStaticProperties());
        $optionsList = \array_filter($optionsList, function (string $name): bool {
            return \str_starts_with($name ,'option');
        });

        $methodOptions = [];

        foreach ($optionsList as $oneOption) :
            $methodOptions[\substr($oneOption,6)] = static::$$oneOption;
        endforeach;

        return new Result (
            fromMethod: static::METHOD_NAME[0],
            byClass: $this::class,
    		election: $this->getElection(),
    		result: $result,
            stats: ($this->getElection()->getStatsVerbosity()->value > StatsVerbosity::NONE->value) ? $this->getStats() : null,
            seats: (static::IS_PROPORTIONAL) ? $this->getElection()->getNumberOfSeats() : null,
            methodOptions: $methodOptions
    	);
    }
}
