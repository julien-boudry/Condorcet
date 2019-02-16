<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace Condorcet;



// Registering native Condorcet Methods implementation
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\BordaCount');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\Copeland');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\DodgsonQuick');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\DodgsonTidemanApproximation');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\DowdallSystem');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\Ftpt');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\InstantRunoff');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\KemenyYoung');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxWinning');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxMargin');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxOpposition');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\RankedPairsMargin');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\RankedPairsWinning');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeWinning');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeMargin');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeRatio');

// Set the default Condorcet Class algorithm
Condorcet::setDefaultMethod('Schulze');

abstract class Condorcet
{

/////////// CONSTANTS ///////////
    public const VERSION = '1.8.2';

    public const CONDORCET_BASIC_CLASS = __NAMESPACE__.'\\Algo\\Methods\\CondorcetBasic';

    protected static ?string $_defaultMethod = null;
    protected static array $_authMethods = [ self::CONDORCET_BASIC_CLASS => (__NAMESPACE__.'\\Algo\\Methods\\CondorcetBasic')::METHOD_NAME ];


/////////// STATICS METHODS ///////////

    // Return library version numer
    public static function getVersion (string $option = 'FULL') : string
    {
        if ($option === 'MAJOR') :
            $version = explode('.', self::VERSION);
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

        return array_column($auth,0);
    }


    // Return the Class default method
    public static function getDefaultMethod () {
        return self::$_defaultMethod;
    }


    // Check if the method is supported
    public static function isAuthMethod (string $method)
    {
        $auth = self::$_authMethods;

        if (empty($method)) :
            throw new CondorcetException (8);
        endif;

        if ( isset($auth[$method]) ) :
            return $method;
        else : // Alias
            foreach ($auth as $class => &$alias) :
                foreach ($alias as &$entry) :
                    if ( strcasecmp($method,$entry) === 0 ) :
                        return $class;
                    endif;
                endforeach;
            endforeach;
        endif;

        return false;
    }


    // Add algos
    public static function addMethod (string $algos) : bool
    {
        // Check algos
        if ( self::isAuthMethod($algos) || !self::testMethod($algos) ) :
            return false;
        endif;

        // Adding algo
        self::$_authMethods[$algos] = $algos::METHOD_NAME;

        if (self::getDefaultMethod() === null) :
            self::setDefaultMethod($algos);
        endif;

        return true;
    }


        // Check if the class Algo. exist and ready to be used
        protected static function testMethod (string $method) : bool
        {
            if ( !class_exists($method) ) :             
                throw new CondorcetException(9);
            endif;

            if ( !is_subclass_of($method, __NAMESPACE__.'\\Algo\\MethodInterface') || !is_subclass_of($method,__NAMESPACE__.'\\Algo\\Method') ) :
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
        if ( ($method = self::isAuthMethod($method)) && $method !== self::CONDORCET_BASIC_CLASS ) :
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
