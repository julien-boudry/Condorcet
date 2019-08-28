<?php
/*
    Condorcet PHP - Election manager and results calculator.
    Designed for the Condorcet method. Integrating a large number of algorithms extending Condorcet. Expandable for all types of voting systems.

    By Julien Boudry and contributors - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
declare(strict_types=1);

namespace CondorcetPHP\Condorcet\Throwable;

use CondorcetPHP\Condorcet\CondorcetVersion;

// Custom Exeption
class CondorcetException extends \Exception
{
    use CondorcetVersion;

    public const CODE_RANGE = [0,1000];

    public const EXCEPTION_CODE = [
        1 => 'Bad candidate format',
        2 => 'The voting process has already started',
        3 => 'This candidate ID is already registered',
        4 => 'This candidate ID do not exist',
        5 => 'Bad vote format | {{ infos1 }}',
        6 => 'You need to specify votes before results',
        7 => 'Your Candidate ID is too long > {{ infos1 }}',
        8 => 'This method do not exist',
        9 => 'The algo class you want has not been defined',
        10 => 'The algo class you want is not correct',
        11 => 'You try to unserialize an object version older than your actual Class version. This is a problematic thing',
        12 => 'You have exceeded the number of votes allowed for this method.',
        13 => 'Formatting error: You must specify an integer',

        15 => 'Input must be valid Json format',
        16 => 'You have exceeded the maximum number of votes allowed per election ({{ infos1 }}).',
        17 => 'Bad tags input format',
        18 => 'New vote can\'t match Candidate of his elections',
        19 => 'This name is not allowed in because of a namesake in the election in which the candidate object participates.',
        20 => 'You need to specify one or more candidates before voting',
        21 => 'Bad vote timestamp format',
        22 => 'This context is not valid',
        23 => 'No Data Handler in use',
        24 => 'A Data Handler is already in use',
        25 => 'Algo class try to use existing alias',
        26 => 'Weight can not be < 1',
        27 => 'The vote constraint class you want has not been defined',
        28 => 'The vote constraint class you want is not correct',
        29 => 'This vote constraint is already registered',

        31 => 'Vote object already registred',
        32 => 'Invalid Input',
        33 => 'This vote is not in this election',

        // DataManager
        50 => 'This entity does not exist.',

        // Algo.
        102 => 'Marquis of Condorcet algortihm can\'t provide a full ranking. But only Winner and Loser.'
    ];

    protected array $_infos;

    public function __construct (int $code = 0, string ...$infos)
    {
        if ($code < static::CODE_RANGE[0] || $code > static::CODE_RANGE[1]) :
            throw new self (0,'Exception class error');
        endif;

        $this->_infos = $infos;

        parent::__construct($this->correspondence($code), $code);
    }

    public function __toString () : string
    {
           return static::class . ": [{$this->code}]: {$this->message} (line: {$this->file}:{$this->line})\n";
    }

    protected function correspondence (int $code) : string
    {
        // Algorithms
        if ($code === 0 || $code === 101) :
            return $this->_infos[0] ?? '';
        endif;

        if ( array_key_exists($code, static::EXCEPTION_CODE) ) :
            return str_replace('{{ infos1 }}', $this->_infos[0] ?? '', static::EXCEPTION_CODE[$code]);
        else :
            return static::EXCEPTION_CODE[0] ?? 'Mysterious Error';
        endif;
    }
}
