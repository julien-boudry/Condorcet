<?php
/*
    Condorcet PHP Class, with Schulze Methods and others !

    By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
    https://github.com/julien-boudry/Condorcet
*/
namespace Condorcet;

use Condorcet\Condorcet;
use Condorcet\CondorcetVersion;

// Custom Exeption
class CondorcetException extends \Exception
{
    use CondorcetVersion;

    protected $_infos;

    public function __construct ($code = 0, $infos = '')
    {
        $this->_infos = $infos;

        parent::__construct($this->correspondence($code), $code);
    }

    public function __toString ()
    {
           return __CLASS__ . ": [{$this->code}]: {$this->message} (line: {$this->file}:{$this->line})\n";
    }

    protected function correspondence ($code)
    {
        // Common
        $error[1] = 'Bad candidate format';
        $error[2] = 'The voting process has already started';
        $error[3] = 'This candidate ID is already registered';
        $error[4] = 'This candidate ID do not exist';
        $error[5] = 'Bad vote format | '.$this->_infos;
        $error[6] = 'You need to specify votes before results';
        $error[7] = 'Your Candidate ID is too long > ' . Election::MAX_LENGTH_CANDIDATE_ID;
        $error[8] = 'This method do not exist';
        $error[9] = 'The algo class you want has not been defined';
        $error[10] = 'The algo class you want is not correct';
        $error[11] = 'You try to unserialize an object version older than your actual Class version. This is a problematic thing';
        $error[12] = 'You have exceeded the number of votes allowed for this method.';
        $error[13] = 'Formatting error: You do not multiply by a number!';
        $error[14] = 'parseVote() must take a string (raw or path) as argument';
        $error[15] = 'Input must be valid Json format';
        $error[16] = 'You have exceeded the maximum number of votes allowed per election ('.$this->_infos.').';
        $error[17] = 'Bad tags input format';
        $error[18] = 'New vote can\'t match Candidate of his elections';
        $error[19] = 'This name is not allowed in because of a namesake in the election in which the object participates.';
        $error[20] = 'You need to specify one or more candidates before voting';
        $error[21] = 'Bad vote timestamp format';

        $error[30] = 'Candidate not in Ranking';


        // Algorithms
        if ($code === 101) :
            $error[101] = explode('|', $this->_infos)[1].' is configured to accept only '.explode('|', $this->_infos)[0].' candidates';
        endif;
        $error[102] = 'Marquis of Condorcet algortihm can\'t provide a full ranking. But only Winner and Loser.';


        if ( array_key_exists($code, $error) )
        {
            return $error[$code];
        }
        else
        {
            return (!is_null($this->_infos)) ? $this->_infos : 'Mysterious Error';
        }
    }
}
