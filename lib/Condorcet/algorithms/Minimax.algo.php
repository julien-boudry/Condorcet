<?php
/*
	Part of the Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.5

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/

namespace Condorcet ;

// Registering algorithm
namespace\Condorcet::add_algos( array('Minimax_Winning','Minimax_Margin') ) ;


// Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
abstract class Minimax
{
	// Config
	protected $_Pairwise ;
	protected $_options_count ;
	protected $_options ;

	// Schulze
	protected $_result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_options_count = $config['_options_count'] ;
		$this->_options = $config['_options'] ;
	}


/////////// PUBLIC ///////////


	// Get the Schulze ranking
	public function get_result ()
	{
		// Cache
		if ( $this->_result !== null )
		{
			return $this->_result ;
		}

			//////


		// Computing
		$this->ComputeMinimax ();


		// Ranking calculation
		$this->calc_ranking () ;


		// Return
		return $this->_result ;
	}


	// Get the Schulze ranking
	public function get_stats ()
	{
		$this->get_result();

			//////

		$explicit = array() ;


		return $explicit ;
	}


		// Get only the Schulze Winner(s)
		public function get_winner ()
		{
			// If there is not Cache
			if ( $this->_result === null )
			{
				$this->get_result();
			}

			return $this->_result[1] ;
		}

		// Get only the Schulze Loser(s)
		public function get_loser ()
		{
			// If there is not Cache
			if ( $this->_result === null )
			{
				$this->get_result();
			}

			return $this->_result[count($this->_result)] ;
		}



/////////// COMPUTE ///////////

	protected function ComputeMinimax ()
	{

	}

	abstract protected function calc_ranking () ;
}

class Minimax_Winning extends MiniMax
{
	protected function calc_ranking ()
	{

	}
}

class Minimax_Margin extends MiniMax
{
	protected function calc_ranking ()
	{
		
	}
}