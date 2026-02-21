# public Algo\Pairwise\Pairwise::compareCandidates

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Pairwise/Pairwise.php#L220)

## Description    

```php
public Algo\Pairwise\Pairwise->compareCandidates ( CondorcetPHP\Condorcet\Candidate|string $a , CondorcetPHP\Condorcet\Candidate|string $b ): int
```

Compare Candidate pairwise to another Candidate.

## Parameters

### **a:** *`CondorcetPHP\Condorcet\Candidate|string`*   
first candidate    

### **b:** *`CondorcetPHP\Condorcet\Candidate|string`*   
candidate to be compared with $a    


## Return value   

*(`int`)* $a wins - $b wins. Negative if a lose, positive if he win or 0 in case of a tie.



## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateExistsException``` 
