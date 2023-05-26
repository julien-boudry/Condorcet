<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\{CondorcetVersion, Election, Result};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{InternalModulesAPI, Throws};
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;
use CondorcetPHP\Condorcet\Relations\HasElection;

// Generic for Algorithms
abstract class Method
{
    use HasElection;
    use CondorcetVersion;

    private const METHOD_NAME = ['abstractMethod'];

    public const IS_PROPORTIONAL = false;

    public static ?int $MaxCandidates = null;

    protected ?Result $Result = null;

    // Internal precision
    public const DECIMAL_PRECISION = 9;

    // Static

    final public static function setOption(string $optionName, array|\BackedEnum|int|float|string $optionValue): true
    {
        $optionVar = 'option'.ucfirst($optionName);

        static::${$optionVar} = $optionValue;

        return true;
    }

    // -------

    #[Throws(CandidatesMaxNumberReachedException::class)]
    public function __construct(Election $mother)
    {
        $this->setElection($mother);

        if (static::$MaxCandidates !== null && $mother->countCandidates() > static::$MaxCandidates) {
            throw new CandidatesMaxNumberReachedException(static::METHOD_NAME[0], static::$MaxCandidates);
        }
    }

    public function __serialize(): array
    {
        $r = get_object_vars($this);
        unset($r['selfElection']);

        return $r;
    }

    #[InternalModulesAPI]
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        $this->compute();

        return $this->Result;
    }

    protected function compute(): void
    {
    }

    abstract protected function getStats(): array;

    protected function createResult(array $result): Result
    {
        $optionsList = array_keys((new \ReflectionClass(static::class))->getStaticProperties());
        $optionsList = array_filter($optionsList, static function (string $name): bool {
            return str_starts_with($name, 'option');
        });

        $methodOptions = [];

        foreach ($optionsList as $oneOption) {
            $methodOptions[mb_substr($oneOption, 6)] = static::${$oneOption};
        }

        return new Result(
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
