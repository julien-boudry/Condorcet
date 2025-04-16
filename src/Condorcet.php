<?php declare(strict_types=1);
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Dev\CondorcetDocumentationGenerator\CondorcetDocAttributes\{Throws};
use CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented;

// Registering native Condorcet Methods implementation

# Classic Methods
Condorcet::addMethod(Algo\Methods\Borda\BordaCount::class);
Condorcet::addMethod(Algo\Methods\Copeland\Copeland::class);
Condorcet::addMethod(Algo\Methods\Dodgson\DodgsonQuick::class);
Condorcet::addMethod(Algo\Methods\Dodgson\DodgsonTidemanApproximation::class);
Condorcet::addMethod(Algo\Methods\Borda\DowdallSystem::class);
Condorcet::addMethod(Algo\Methods\InstantRunoff\InstantRunoff::class);
Condorcet::addMethod(Algo\Methods\KemenyYoung\KemenyYoung::class);
Condorcet::addMethod(Algo\Methods\Lotteries\RandomBallot::class);
Condorcet::addMethod(Algo\Methods\Lotteries\RandomCandidates::class);
Condorcet::addMethod(Algo\Methods\Majority\FirstPastThePost::class);
Condorcet::addMethod(Algo\Methods\Majority\MultipleRoundsSystem::class);
Condorcet::addMethod(Algo\Methods\Minimax\MinimaxWinning::class);
Condorcet::addMethod(Algo\Methods\Minimax\MinimaxMargin::class);
Condorcet::addMethod(Algo\Methods\Minimax\MinimaxOpposition::class);
Condorcet::addMethod(Algo\Methods\RankedPairs\RankedPairsMargin::class);
Condorcet::addMethod(Algo\Methods\RankedPairs\RankedPairsWinning::class);
Condorcet::addMethod(Algo\Methods\Schulze\SchulzeWinning::class);
Condorcet::addMethod(Algo\Methods\Schulze\SchulzeMargin::class);
Condorcet::addMethod(Algo\Methods\Schulze\SchulzeRatio::class);
Condorcet::addMethod(Algo\Methods\Smith\SchwartzSet::class);
Condorcet::addMethod(Algo\Methods\Smith\SmithSet::class);

# Proportional Methods
Condorcet::addMethod(Algo\Methods\STV\SingleTransferableVote::class);
Condorcet::addMethod(Algo\Methods\STV\CPO_STV::class);
Condorcet::addMethod(Algo\Methods\LargestRemainder\LargestRemainder::class);
Condorcet::addMethod(Algo\Methods\HighestAverages\Jefferson::class);
Condorcet::addMethod(Algo\Methods\HighestAverages\SainteLague::class);

// Set the default Condorcet Class algorithm
Condorcet::setDefaultMethod(Algo\Methods\Schulze\SchulzeWinning::class);

abstract class Condorcet
{
    /////////// CONSTANTS ///////////
    final public const AUTHOR = 'Julien Boudry and contributors';
    final public const HOMEPAGE = 'https://github.com/julien-boudry/Condorcet';

    /**
     * @api
     */
    final public const VERSION = '5.0.0';

    /**
     * @api
     */
    final public const CONDORCET_BASIC_CLASS = Algo\Methods\CondorcetBasic::class;

    protected static ?string $defaultMethod = null;
    protected static array $authMethods = [self::CONDORCET_BASIC_CLASS => (Algo\Methods\CondorcetBasic::class)::METHOD_NAME];

    /**
     * @api
     */
    public static bool $UseTimer = false;


    /////////// STATICS METHODS ///////////

    // Return library version number
    /**
     * Get the library version.
     * @api
     * @return mixed Condorcet PHP version.
     * @see Election::getObjectVersion
     * @param $major * true will return : 2.0, false will return : 2.0.0.
     */
    public static function getVersion(
        bool $major = false
    ): string {
        if ($major) {
            $version = explode('.', self::VERSION);
            return $version[0] . '.' . $version[1];
        } else {
            return self::VERSION;
        }
    }

    // Return an array with auth methods
    /**
     * Get a list of supported algorithm.
     * @api
     * @return mixed Populated by method string name. You can use it on getResult ... and others methods.
     * @see Condorcet::isAuthMethod, Condorcet::getMethodClass
     * @param $basic Include or not the natural Condorcet base algorithm.
     * @param $withNonDeterministicMethods Include or not non deterministic methods.
     */
    public static function getAuthMethods(
        bool $basic = false,
        bool $withNonDeterministicMethods = true
    ): array {
        $auth = self::$authMethods;

        // Don't show Natural Condorcet
        if (!$basic) {
            unset($auth[self::CONDORCET_BASIC_CLASS]);
        }

        // Exclude Deterministic
        if (!$withNonDeterministicMethods) {
            $auth = array_filter($auth, static fn(string $m): bool => $m::IS_DETERMINISTIC, \ARRAY_FILTER_USE_KEY);
        }

        return array_column($auth, 0);
    }


    // Return the Class default method
    /**
     * Return the Condorcet static default method.
     * @api
     * @return mixed Method name.
     * @see Condorcet::getAuthMethods, Condorcet::setDefaultMethod
     */
    public static function getDefaultMethod(): ?string
    {
        return self::$defaultMethod;
    }


    // Check if the method is supported
    /**
     * Return the full class path for a method.
     * @api
     * @return mixed Return null is method not exist.
     * @throws VotingMethodIsNotImplemented
     * @see Condorcet::getAuthMethods
     * @param $method A valid method name.
     */
    public static function getMethodClass(
        string $method
    ): ?string {
        $auth = self::$authMethods;

        if (empty($method)) {
            throw new VotingMethodIsNotImplemented('no method name given');
        }

        if (isset($auth[$method])) {
            return $method;
        } else { // Alias
            foreach ($auth as $class => $alias) {
                foreach ($alias as $entry) {
                    if (strcasecmp($method, $entry) === 0) {
                        return $class;
                    }
                }
            }
        }

        return null;
    }
    /**
     * Test if a method is in the result set of Condorcet::getAuthMethods.
     * @api
     * @return mixed True / False
     * @see Condorcet::getMethodClass, Condorcet::getAuthMethods
     * @param $method A valid method name or class.
     */
    public static function isAuthMethod(
        string $method
    ): bool {
        return self::getMethodClass($method) !== null;
    }


    // Add algos
    /**
     * If you create your own Condorcet Algo. You will need it !
     * @api
     * @return mixed True on Success. False on failure.
     * @see Condorcet::isAuthMethod, Condorcet::getMethodClass
     * @param $methodClass The class name implementing your method. The class name includes the namespace it was declared in (e.g. Foo\Bar).
     */
    public static function addMethod(
        string $methodClass
    ): bool {
        // Check algos
        if (self::isAuthMethod($methodClass) || !self::testMethod($methodClass)) {
            return false;
        }

        // Adding algo
        self::$authMethods[$methodClass] = $methodClass::METHOD_NAME;

        if (self::getDefaultMethod() === null) {
            self::setDefaultMethod($methodClass);
        }

        return true;
    }


    // Check if the class Algo. exist and ready to be used
    protected static function testMethod(string $method): bool
    {
        if (!class_exists($method)) {
            throw new VotingMethodIsNotImplemented("no class found for '{$method}'");
        }

        if (!is_subclass_of($method, Algo\MethodInterface::class) || !is_subclass_of($method, Algo\Method::class)) {
            throw new VotingMethodIsNotImplemented('the given class is not correct');
        }

        foreach ($method::METHOD_NAME as $alias) {
            if (self::isAuthMethod($alias)) {
                throw new VotingMethodIsNotImplemented('the given class is using an existing alias');
            }
        }

        return true;
    }


    // Change default method for this class.
    /**
     * Put a new static method by default for the news Condorcet objects.
     * @api
     * @return mixed In case of success, return TRUE
     * @see Condorcet::getDefaultMethod
     * @param $method A valid method name or class.
     */
    public static function setDefaultMethod(
        string $method
    ): bool {
        if (($method = self::getMethodClass($method)) && $method !== self::CONDORCET_BASIC_CLASS) {
            self::$defaultMethod = $method;
            return true;
        } else {
            return false;
        }
    }

    /** @internal */
    public static function validateAlternativeWinnerLoserMethod(?string $substitution): string
    {
        if ($substitution !== null) {
            if (self::isAuthMethod($substitution)) {
                $algo = $substitution;
            } else {
                throw new VotingMethodIsNotImplemented("No class found for method '{$substitution}'");
            }
        } else {
            $algo = self::CONDORCET_BASIC_CLASS;
        }

        return $algo;
    }
}
