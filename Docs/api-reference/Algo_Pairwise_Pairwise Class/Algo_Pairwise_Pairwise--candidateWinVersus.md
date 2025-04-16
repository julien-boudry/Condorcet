## public Algo\Pairwise\Pairwise::candidateWinVersus

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Algo/Pairwise/Pairwise.php#L212)

### Description    

```php
public Algo\Pairwise\Pairwise->candidateWinVersus ( CondorcetPHP\Condorcet\Candidate|string $candidate , CondorcetPHP\Condorcet\Candidate|string $opponent ): bool
```

Compare Candidate pairwise to another Candidate.
    

#### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string`*   
the candidate to be compared    


#### **opponent:** *`CondorcetPHP\Condorcet\Candidate|string`*   
the candidate to be compared with $candidate    


### Return value   

*(`bool`)* true if $a win, false if it lose or tie



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateExistsException``` 
