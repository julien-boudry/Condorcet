<?php
/*
	Copeland part of the Condorcet PHP Class

	Version : 0.9

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Ranker Pairs is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Ranked_Pairs
class RankedPairs implements namespace\Condorcet_Algo
{
	// Config
	protected $_Pairwise ;
	protected $_CandidatesCount ;
	protected $_Candidates ;

	// Ranked Pairs
	protected $_PairwiseSort ;
	protected $_arcs ;
	protected $_Result ;


	public function __construct (array $config)
	{
		$this->_Pairwise = $config['_Pairwise'] ;
		$this->_CandidatesCount = $config['_CandidatesCount'] ;
		$this->_Candidates = $config['_Candidates'] ;
	}


/////////// PUBLIC ///////////


	// Get the Ranked Pairs ranking
	public function getResult ($options = null)
	{
		// Cache
		if ( $this->_Result !== null )
		{
			return $this->_Result ;
		}

			//////

		// Sort pairwise
		$this->_PairwiseSort = namespace\Condorcet::makeStatic_PairwiseSort($this->_Pairwise) ;

		// Ranking calculation
		$this->makeRanking() ;


		// Return
		// return $this->_PairwiseSort ;
	}


	// Get the Ranked Pair ranking
	public function getStats ()
	{
		$this->getResult();

			//////

		$explicit = array() ;

		return $explicit ;
	}



/////////// COMPUTE ///////////


	//:: RANKED PAIRS ALGORITHM. :://

	protected function makeRanking ()
	{
		$this->makeArcs();

	}

	protected function makeArcs ()
	{
		$this->_arcs = array() ;

		foreach ($this->_PairwiseSort as $wise => $strong)
		{
			$ord = explode ('>',$wise);

			$this->_arcs[] = array('from' => $ord[0], 'to' => $ord[1], 'strong' => $strong['score']) ;
		}

		$candidate_fire = array() ;
		foreach ($this->_arcs as $value)
		{
			if (!in_array($value['from'], $candidate_fire, true))
			{
				$candidate_fire[] = $value['from'] ;
			}
		}

		foreach ($candidate_fire as $candidate)
		{
			$this->checkDelArc($candidate);
		}
	}

		protected function checkDelArc ($candidate, $construct = '', $done = null)
		{
			if ($done === null){$done = array();}

			// Deleting arc
			if (!empty($done))
			{
				$test_cycle = explode('-', $construct);
				$count_cycle = array_count_values($test_cycle) ;

				if ($count_cycle[$candidate] > 1) // There is a cycle
				{
					$this->delArc($test_cycle, $candidate);
					var_dump($construct);

					return ;
				}				
			}

			foreach ($this->_arcs as $new_arc_key => $new_arc)
			{
				if (!in_array($new_arc_key, $done, true))
				{
					if ($new_arc['from'] !== $candidate)
					{
						continue ;
					}

					$candidat = $new_arc['to'];

					$done_next = $done ;
					$done_next[] = $new_arc_key ;

					// Recursive
					$construct_next = $construct ;
					if ($construct_next !== ''){$construct_next .= '-';}
					$this->checkDelArc($candidate, $construct_next.$new_arc['from'].'-'.$new_arc['to'], $done_next);
				}
			}
			
		}

		protected function delArc ($test_cycle, $candidate)
		{

		}

		// foreach ($this->_arcs as &$value)
		// {
		// 	foreach ($value as $key => &$infos)
		// 	{
		// 		if ($key === 'to' || $key === 'from')
		// 		{
		// 			$infos = namespace\Condorcet::getStatic_CandidateId($infos, $this->_Candidates);
		// 		}
		// 	}
		// }

}

// Registering algorithm
namespace\Condorcet::addAlgos('RankedPairs') ;
