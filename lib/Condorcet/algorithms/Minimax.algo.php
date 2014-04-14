<?php
/*
	Part of the Condorcet PHP Class, with Schulze Methods and others !

	Version : 0.7

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/

namespace Condorcet ;

// Registering algorithm
namespace\Condorcet::add_algos( array('Minimax_Winning','Minimax_Margin', 'Minimax_Opposition') ) ;


// Schulze is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Schulze_method
abstract class Minimax
{
	// Config
	protected $_Pairwise ;
	protected $_options_count ;
	protected $_options ;

	// Minimax
	protected $_stats ;
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

		foreach ($this->_stats as $option_key => $value)
		{
			$explicit[namespace\Condorcet::get_static_option_id($option_key, $this->_options)] = $value ;
		}

		return $explicit ;
	}



/////////// COMPUTE ///////////

	protected function ComputeMinimax ()
	{
		$this->_stats = array() ;

		foreach ($this->_options as $option_key => $option_id)
		{			
			$lose_score			= array() ;
			$margin_score		= array() ;
			$opposition_score	= array() ;

			foreach ($this->_Pairwise[$option_key]['lose'] as $key_lose => $value_lose)
			{
				// Margin
				$margin = $value_lose - $this->_Pairwise[$option_key]['win'][$key_lose] ;
				$margin_score[] = $margin ;

				// Winning
				if ($margin > 0)
				{
					$lose_score[] = $value_lose ;
				}

				// Opposition
				$opposition_score[] = $value_lose ;
			}

			// Write result
				// Winning
			if (!empty($lose_score)) {$this->_stats[$option_key]['winning'] = max($lose_score) ;}
			else {$this->_stats[$option_key]['winning'] = 0 ;}
			
				// Margin
			$this->_stats[$option_key]['margin'] = max($margin_score) ;

				// Opposition
			$this->_stats[$option_key]['opposition'] = max($opposition_score) ;
		}
	}

	abstract protected function calc_ranking () ;

	protected static function calc_ranking_method ($type, array $stats, $options)
	{
		$result = array() ;
		$values = array() ;

		foreach ($stats as $candidate_key => $candidate_stats)
		{
			$values[$candidate_key] = $candidate_stats[$type] ;
		}


		for ($rank = 1 ; !empty($values) ; $rank++)
		{
			$looking = min($values);

			foreach ($values as $candidate_key => $candidate_stats)
			{
				if ($candidate_stats === $looking)
				{
					$result[$rank][] = namespace\Condorcet::get_static_option_id ($candidate_key, $options) ;

					unset($values[$candidate_key]);
				}
			}
		}

		foreach ($result as $rank => $options_name)
		{
			$result[$rank] = implode(',', $options_name);
		}

		return $result ;
	}
}

class Minimax_Winning extends Minimax
{
	protected function calc_ranking ()
	{
		$this->_result = self::calc_ranking_method('winning', $this->_stats, $this->_options) ;
	}
}

class Minimax_Margin extends Minimax
{
	protected function calc_ranking ()
	{
		$this->_result = self::calc_ranking_method('margin', $this->_stats, $this->_options) ;
	}
}

// Beware, this method is not a Condorcet method ! Winner can be different than Condorcet Basic method
class Minimax_Opposition extends Minimax
{
	protected function calc_ranking ()
	{
		$this->_result = self::calc_ranking_method('opposition', $this->_stats, $this->_options) ;
	}
}