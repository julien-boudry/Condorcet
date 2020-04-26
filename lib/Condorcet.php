<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet;

use CondorcetPHP\Condorcet\Throwable\CondorcetException;

// Registering native Condorcet Methods implementation
Condorcet::addMethod( Algo\Methods\Borda\BordaCount::class );
Condorcet::addMethod( Algo\Methods\Copeland\Copeland::class );
Condorcet::addMethod( Algo\Methods\Dodgson\DodgsonQuick::class );
Condorcet::addMethod( Algo\Methods\Dodgson\DodgsonTidemanApproximation::class );
Condorcet::addMethod( Algo\Methods\Borda\DowdallSystem::class );
Condorcet::addMethod( Algo\Methods\InstantRunoff\InstantRunoff::class );
Condorcet::addMethod( Algo\Methods\KemenyYoung\KemenyYoung::class );
Condorcet::addMethod( Algo\Methods\Majority\FirstPastThePost::class );
Condorcet::addMethod( Algo\Methods\Majority\TwoRoundSystem::class );
Condorcet::addMethod( Algo\Methods\Minimax\MinimaxWinning::class );
Condorcet::addMethod( Algo\Methods\Minimax\MinimaxMargin::class );
Condorcet::addMethod( Algo\Methods\Minimax\MinimaxOpposition::class );
Condorcet::addMethod( Algo\Methods\RankedPairs\RankedPairsMargin::class );
Condorcet::addMethod( Algo\Methods\RankedPairs\RankedPairsWinning::class );
Condorcet::addMethod( Algo\Methods\Schulze\SchulzeWinning::class );
Condorcet::addMethod( Algo\Methods\Schulze\SchulzeMargin::class );
Condorcet::addMethod( Algo\Methods\Schulze\SchulzeRatio::class );

// Set the default Condorcet Class algorithm
Condorcet::setDefaultMethod('Schulze');

abstract class Condorcet
{

/////////// CONSTANTS ///////////
    public const VERSION = '2.1.1';

    public const CONDORCET_BASIC_CLASS = Algo\Methods\CondorcetBasic::class;

    protected static ?string $_defaultMethod = null;
    protected static array $_authMethods = [ self::CONDORCET_BASIC_CLASS => (Algo\Methods\CondorcetBasic::class)::METHOD_NAME ];


/////////// STATICS METHODS ///////////

    // Return library version number
    public static function getVersion (bool $major = false) : string
    {
        if ($major === true) :
            $version = \explode('.', self::VERSION);
            return $version[0].'.'.$version[1];
        else :
            return self::VERSION;
        endif;
    }

    // Return an array with auth methods
    public static function getAuthMethods (bool $basic = false) : array
    {
        $auth = self::$_authMethods;

        // Don't show Natural Condorcet
        if (!$basic) :
            unset($auth[self::CONDORCET_BASIC_CLASS]);
        endif;

        return \array_column($auth,0);
    }


    // Return the Class default method
    public static function getDefaultMethod () : ?string {
        return self::$_defaultMethod;
    }


    // Check if the method is supported
    public static function getMethodClass (string $method) : ?string
    {
        $auth = self::$_authMethods;

        if (empty($method)) :
            throw new CondorcetException (8);
        endif;

        if ( isset($auth[$method]) ) :
            return $method;
        else : // Alias
            foreach ($auth as $class => $alias) :
                foreach ($alias as $entry) :
                    if ( \strcasecmp($method,$entry) === 0 ) :
                        return $class;
                    endif;
                endforeach;
            endforeach;
        endif;

        return null;
    }

    public static function isAuthMethod (string $method) : bool
    {
        return self::getMethodClass($method) !== null ;
    }


    // Add algos
    public static function addMethod (string $methodClass) : bool
    {
        // Check algos
        if ( self::isAuthMethod($methodClass) || !self::testMethod($methodClass) ) :
            return false;
        endif;

        // Adding algo
        self::$_authMethods[$methodClass] = $methodClass::METHOD_NAME;

        if (self::getDefaultMethod() === null) :
            self::setDefaultMethod($methodClass);
        endif;

        return true;
    }


        // Check if the class Algo. exist and ready to be used
        protected static function testMethod (string $method) : bool
        {
            if ( !\class_exists($method) ) :
                throw new CondorcetException(9);
            endif;

            if ( !\is_subclass_of($method, Algo\MethodInterface::class) || !\is_subclass_of($method, Algo\Method::class) ) :
                throw new CondorcetException(10);
            endif;

            foreach ($method::METHOD_NAME as $alias) :
                if (self::isAuthMethod($alias)) :
                    throw new CondorcetException(25);
                endif;
            endforeach;

            return true;
        }


    // Change default method for this class.
    public static function setDefaultMethod (string $method) : bool
    {
        if ( ($method = self::getMethodClass($method)) && $method !== self::CONDORCET_BASIC_CLASS ) :
            self::$_defaultMethod = $method;
            return true;
        else :
            return false;
        endif;
    }

    public static function condorcetBasicSubstitution (?string $substitution) : string
    {
        if ( $substitution !== null ) :
            if ( Condorcet::isAuthMethod($substitution) ) :
                $algo = $substitution;
            else :
                throw new CondorcetException(9,$substitution);
            endif;
        else :
            $algo = Condorcet::CONDORCET_BASIC_CLASS;
        endif;

        return $algo;
    }
}
