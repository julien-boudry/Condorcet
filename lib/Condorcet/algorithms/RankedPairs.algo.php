<?php
/*
	Ranked Pairs part of the Condorcet PHP Class

	Last modified at: Condorcet Class v0.10

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class
*/

namespace Condorcet ;


// Ranker Pairs is a Condorcet Algorithm | http://en.wikipedia.org/wiki/Ranked_Pairs
class RankedPairs implements namespace\Condorcet_Algo
{
	use namespace\BaseAlgo;


	// Ranked Pairs
	protected $_PairwiseSort ;
	protected $_Arcs ;
	protected $_Stats ;
	protected $_StatsDone = false ;
	protected $_Result ;


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
		$this->_PairwiseSort = namespace\Condorcet::makeStatic_PairwiseSort($this->_selfElection->getPairwise(false)) ;

		// Ranking calculation
		$this->makeArcs();

		$this->_Result = array() ;

		$rang = 1 ;
		while (count($this->_Result) < $this->_selfElection->countCandidates())
		{
			$winner = $this->getOneWinner();

			foreach ($this->_Arcs as $ArcKey => $Arcvalue)
			{
				if ($Arcvalue['from'] === $winner || $Arcvalue['to'] === $winner )
				{
					unset($this->_Arcs[$ArcKey]);
				}
			}

			$this->_Result[$rang++] = $winner;
		}

		// Return
		return $this->_Result ;
	}

	// Get the Ranked Pair ranking
	public function getStats ()
	{
		$this->getResult();

			//////

		if (!$this->_StatsDone)
		{
			foreach ($this->_Stats as $ArcKey => &$Arcvalue)
			{
				foreach ($Arcvalue as $key => &$value)
				{
					if ($key === 'from' || $key === 'to')
					{
						$value = $this->_selfElection->getCandidateId($value);
					}
				}
			}

			$this->_StatsDone = true ;
		}

		return $this->_Stats ;
	}



/////////// COMPUTE ///////////


	//:: RANKED PAIRS ALGORITHM. :://

	protected function getOneWinner ()
	{
		foreach ($this->_selfElection->getCandidatesList() as $candidateKey => $candidateId)
		{
			if (!in_array($candidateKey, $this->_Result, true))
			{
				$winner = true ;
				foreach ($this->_Arcs as $ArcKey => $ArcValue)
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
		$this->_Arcs = array() ;

		foreach ($this->_PairwiseSort as $wise => $strength)
		{
			$ord = explode ('>',$wise);

			$this->_Arcs[] = array('from' => intval($ord[0]), 'to' => intval($ord[1]), 'strength' => $strength['score']) ;
		}

		foreach ($this->_Arcs as $key => $value)
		{
			if (!isset($this->_Arcs[$key]))
				{continue ;}

			$this->checkingArc($value['from'], $value['to'], $value['from'].'-'.$value['to'], array($key));
		}

		$this->_Stats = $this->_Arcs ;
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

			foreach ($this->_Arcs as $new_arc_key => $new_arc)
			{
				if (!isset($this->_Arcs[$new_arc_key]))
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


			foreach ($this->_Arcs as $key => $value)
			{
				if ($value['from'] == $to_del[0] && $value['to'] == $to_del[1])
				{
					unset($this->_Arcs[$key]);
				}
			}
		}

}

// Registering algorithm
namespace\Condorcet::addAlgos('RankedPairs') ;
