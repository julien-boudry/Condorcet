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

		$this->_Result = array() ;

		while (count($this->_Result) < $this->_CandidatesCount)
		{
			$winner = $this->getOneWinner();

			foreach ($this->_arcs as $ArcKey => $Arcvalue)
			{
				if ($Arcvalue['from'] === $winner || $Arcvalue['to'] === $winner )
				{
					unset($this->_arcs[$ArcKey]);
				}
			}

			$this->_Result[] = $winner;
		}
	}

	protected function getOneWinner ()
	{
		foreach ($this->_Candidates as $candidateKey => $candidateId)
		{
			if (!in_array($candidateKey, $this->_Result, true))
			{
				$winner = true ;
				foreach ($this->_arcs as $ArcKey => $ArcValue)
				{
					if ($ArcValue['to'] === $candidateKey)
						{ $winner = false ;}
				}

				if ($winner)
				{
					return $candidateKey ;
				}
			}
		}
	}

	protected function makeArcs ()
	{
		$this->_arcs = array() ;

		foreach ($this->_PairwiseSort as $wise => $strong)
		{
			$ord = explode ('>',$wise);

			$this->_arcs[] = array('from' => intval($ord[0]), 'to' => intval($ord[1]), 'strong' => $strong['score']) ;
		}

		foreach ($this->_arcs as $key => $value)
		{
			if (!isset($this->_arcs[$key]))
				{continue ;}

			$this->checkingArc($value['from'], $value['to'], $value['from'].'-'.$value['to'], array($key));
		}

	}

		protected function checkingArc ($candidate, $candidate_next, $construct, $done)
		{
			// Deleting arc
			if (count($done) > 1)
			{
				$test_cycle = explode('-', $construct);
				$count_cycle = array_count_values($test_cycle) ;

				if ($count_cycle[$candidate] > 1) // There is a cycle
				{					
					$this->delArc($test_cycle, $candidate);

					return ;
				}
			}

			foreach ($this->_arcs as $new_arc_key => $new_arc)
			{
				if (!isset($this->_arcs[$new_arc_key]))
					{continue ;}

				if (!in_array($new_arc_key, $done, true))
				{
					if ($new_arc['from'] !== $candidate_next)
					{
						continue ;
					}

					$done_next = $done ;
					$done_next[] = $new_arc_key ;

					// Recursive
					$this->checkingArc($candidate, $new_arc['to'], $construct.'-'.$new_arc['to'], $done_next);

				}
			}			
		}

		protected function delArc ($test_cycle, $candidate)
		{
			$cycles = array() ;

			$i = 1 ; $phase = false ;
			foreach ($test_cycle as $value)
			{
				if ($i === 1 && !$phase)
				{
					$cycles[$i] = '' ;
					$cycles[$i] .= $value ;

					$phase = !$phase ;

					continue ;
				}

				///

				$cycles[$i] .= '>'.$value ;

				if ($i + 1 < count($test_cycle))
				{
					$cycles[$i + 1] = '' ;
					$cycles[$i + 1] .= $value ;
				}

				$i++ ;
			}

			$score = array() ;
			foreach ($cycles as $key => $value)
			{
				$score[$key] = $this->_PairwiseSort[$value]['score'] ;
			}

			$to_del = $cycles[array_search(min($score), $score, true)] ;
			$to_del = explode ('>', $to_del);


			foreach ($this->_arcs as $key => $value)
			{
				if ($value['from'] == $to_del[0] && $value['to'] == $to_del[1])
				{
					unset($this->_arcs[$key]);
				}
			}

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
