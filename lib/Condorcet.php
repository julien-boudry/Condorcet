<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    Version: 0.96

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

use Condorcet\CondorcetException;
use Condorcet\Election;

// Registering native Condorcet Methods implementation
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\Copeland');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\KemenyYoung');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxWinning');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxMargin');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\MinimaxOpposition');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\RankedPairs');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeWinning');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeMargin');
Condorcet::addMethod(__NAMESPACE__.'\\Algo\\Methods\\SchulzeRatio');

// Set the default Condorcet Class algorithm
Condorcet::setDefaultMethod('Schulze');

abstract class Condorcet
{

/////////// CONSTANTS ///////////
        const VERSION = '0.96';

        const ENV = 'DEV';

        const CONDORCET_BASIC_CLASS = 'Condorcet\\Algo\\Methods\\CondorcetBasic';

        protected static $_defaultMethod = null;
        protected static $_authMethods = [ self::CONDORCET_BASIC_CLASS => ['CondorcetBasic'] ];


/////////// STATICS METHODS ///////////

    // Return library version numer
    public static function getVersion ($options = 'FULL')
    {
            switch ($options)
            {
                case 'MAJOR':
                    $version = explode('.', self::VERSION);
                    return $version[0].'.'.$version[1];

                case 'ENV':
                    return ( (self::ENV === 'DEV') ? self::ENV . ' - ' : '') . self::VERSION;

                default:
                    return self::VERSION;
            }
    }

    // Return an array with auth methods
    public static function getAuthMethods ($basic = false)
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
    public static function isAuthMethod ($method)
    {
        $auth = self::$_authMethods;

        if (!is_string($method) || empty($method)) :
            throw new CondorcetException (8);
        endif;

        if ( isset($auth[$method]) ) :
            return $method;
        else : // Alias
            foreach ($auth as $class => &$alias) :
                foreach ($alias as &$entry) :
                    if (strtoupper($method) === $entry) :
                        return $class;
                    endif;
                endforeach;
            endforeach;
        endif;

        return false;
    }


    // Add algos
    public static function addMethod ($algos)
    {
        $to_add = [];

        // Check algos
        if ( !is_string($algos) ) :
            return false;
        elseif ( self::isAuthMethod($algos) || !self::testMethod($algos) ) :
            return false;
        endif;

        // Adding algo
        self::$_authMethods[$algos] = explode(',',strtoupper($algos::METHOD_NAME));

        if (self::getDefaultMethod() === null) :
            self::setDefaultMethod($algos);
        endif;

        return true;
    }


        // Check if the class Algo. exist and ready to be used
        protected static function testMethod ($method)
        {
            if ( !class_exists($method) ) :             
                throw new CondorcetException(9);
            endif;

            if ( !is_subclass_of($method, __NAMESPACE__.'\\Algo\\MethodInterface') || !is_subclass_of($method,__NAMESPACE__.'\\Algo\\Method') ) :
                throw new CondorcetException(10);
            endif;

            return true;
        }


    // Change default method for this class.
    public static function setDefaultMethod ($method)
    {       
        if ( ($method = self::isAuthMethod($method)) && $method !== self::CONDORCET_BASIC_CLASS ) :
            self::$_defaultMethod = $method;
            return self::getDefaultMethod();
        else :
            return false;
        endif;
    }

    // Simplify Condorcet Var_Dump. Transform object to String.
    public static function format ($input, $out = true, $convertObject = true)
    {
        if (is_object($input)) :
            
            $r = $input;

            if ($convertObject) :
                if ($input instanceof Candidate) :
                    $r = (string) $input;
                elseif ($input instanceof Vote) :
                    $r = $input->getRanking();
                endif;
            endif;

        elseif (!is_array($input)) :
            $r = $input;
        else :
            foreach ($input as $key => $line) :
                $input[$key] = self::format($line,false,$convertObject);
            endforeach;

            if (count($input) === 1 && count(reset($input)) === 1):
                $r = reset($input);
            else:
                $r = $input;
            endif;
        endif;

            ///

        if ($out): var_dump($r); endif;
        
        return $r;
    }
}