<?php
/*
	Part of the Condorcet PHP Class, with Copeland Methods and others !

	Version : 0.4

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Copeland-PHP_Class 
*/


// Registering algorithm
Condorcet::add_algos('Copeland') ;


// Copeland is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Copeland_method
class Condorcet_Copeland
{
	// Config
	protected $_Pairwise ;
	protected $_options_count ;
	protected $_options ;

	// Copeland
	protected $_Copeland_comparison ;
	protected $_Copeland_result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_options_count = $config['_options_count'] ;
		$this->_options = $config['_options'] ;
	}



/////////// PUBLIC ///////////


	// Get the Coepland ranking
	public function get_result ()
	{
		// Cache
		if ( $this->_Copeland_result !== null )
		{
			return $this->_Copeland_result ;
		}

			//////




		// Return
		return $this->_Copeland_result ;
	}


	// Get the Copeland ranking
	public function get_stats ()
	{
		$this->get_result();

			//////

		$explicit = array() ;

		return $explicit ;
	}


		// Get only the Copeland Winner(s)
		public function get_winner ()
		{
			// If there is not Cache
			if ( $this->_Copeland_result === null )
			{
				$this->get_result();
			}

			return $this->_Copeland_result[1] ;
		}

		// Get only the Copeland Loser(s)
		public function get_loser ()
		{
			// If there is not Cache
			if ( $this->_Copeland_result === null )
			{
				$this->get_result();
			}

			return $this->_Copeland_result[count($this->_Copeland_result)] ;
		}



/////////// COMPUTE ///////////


	//:: COPELAND ALGORITHM. :://




}