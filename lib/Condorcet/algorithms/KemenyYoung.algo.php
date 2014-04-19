<?php
/*
	Part of the Condorcet PHP Class, with Copeland Methods and others !

	Version : 0.8

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Copeland-PHP_Class 
*/

namespace Condorcet ;

// Registering algorithm
namespace\Condorcet::addAlgos('KemenyYoung') ;


// Kemeny Young is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Kemeny%E2%80%93Young_method
class KemenyYoung
{
	// Config
	protected $_Pairwise ;
	protected $_CandidatesCount ;
	protected $_Candidates ;

	// Kemeny Young
	protected $_Result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_CandidatesCount = $config['_CandidatesCount'] ;
		$this->_Candidates = $config['_Candidates'] ;
	}


/////////// PUBLIC ///////////


	// Get the Coepland ranking
	public function getResult ()
	{
		// Cache
		if ( $this->_Result !== null )
		{
			return $this->_Result ;
		}

			//////


		// Return
		return $this->_Result ;
	}


	// Get the Copeland ranking
	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		return $explicit ;
	}



/////////// COMPUTE ///////////


	//:: COPELAND ALGORITHM. :://

	protected function makeRanking ()
	{

	}

}
