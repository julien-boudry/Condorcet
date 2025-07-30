<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet\Algo;

use CondorcetPHP\Condorcet\{CondorcetVersion, Election, Result};
use CondorcetPHP\Condorcet\Algo\Stats\{EmptyStats, StatsInterface};
use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Throws};
use CondorcetPHP\Condorcet\Throwable\CandidatesMaxNumberReachedException;
use CondorcetPHP\Condorcet\Relations\HasElection;
use Random\Randomizer;

/**
 * Generic for Algorithms
 * @internal
 */
abstract class Method
{
    use HasElection;
    use CondorcetVersion;

    public const bool IS_PROPORTIONAL = false;
    public const bool IS_DETERMINISTIC = true;

    public static ?int $MaxCandidates = null;

    protected ?Result $Result = null;

    // Internal precision
    public const int DECIMAL_PRECISION = 9;

    // Static

    final public static function setOption(string $optionName, array|\BackedEnum|int|float|string|Randomizer $optionValue): true
    {
        $optionVar = 'option' . mb_ucfirst($optionName);

        static::${$optionVar} = $optionValue;

        return true;
    }

    // -------
    /**
     * @throws CandidatesMaxNumberReachedException
     */
    public function __construct(Election $mother)
    {
        $this->setElection($mother);

        if (static::$MaxCandidates !== null && $mother->countCandidates() > static::$MaxCandidates) {
            throw new CandidatesMaxNumberReachedException(static::METHOD_NAME[0], static::$MaxCandidates); // @phpstan-ignore classConstant.notFound
        }
    }

    public function __serialize(): array
    {
        $r = get_object_vars($this);
        unset($r['selfElection']);

        return $r;
    }

    /**
     * @internal
     */
    public function getResult(): Result
    {
        // Cache
        if ($this->Result !== null) {
            return $this->Result;
        }

        $this->compute();

        return $this->Result;
    }

    protected function compute(): void {}

    abstract protected function getStats(): StatsInterface;

    protected function createResult(array $result): Result
    {
        $optionsList = array_keys(new \ReflectionClass(static::class)->getStaticProperties());
        $optionsList = array_filter($optionsList, static fn(string $name): bool => str_starts_with($name, 'option'));

        $methodOptions = [];

        foreach ($optionsList as $oneOption) {
            $methodOptions[mb_substr($oneOption, 6)] = static::${$oneOption};
        }

        return new Result(
            fromMethod: static::METHOD_NAME[0], // @phpstan-ignore classConstant.notFound
            byClass: static::class,
            election: $this->getElection(),
            rawRanking: $result,
            stats: $this->getElection()->statsVerbosity->value > StatsVerbosity::NONE->value ? $this->getStats() : new EmptyStats,
            seats: (static::IS_PROPORTIONAL) ? $this->getElection()->seatsToElect : null,
            methodOptions: $methodOptions
        );
    }
}
