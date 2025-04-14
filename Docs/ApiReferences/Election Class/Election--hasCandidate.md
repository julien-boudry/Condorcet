## public Election::hasCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L101)

### Description    

```php
public Election->hasCandidate ( CondorcetPHP\Condorcet\Candidate|string $candidate [, bool $strictMode = true] ): bool
```

Check if a candidate is already registered for this election.
    

#### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string`*   
Candidate object or candidate name as a string. The candidate name as a string only works if the strict mode is disabled.    


#### **strictMode:** *`bool`*   
Strict comparison mode. In strict mode, candidate objects are compared strictly and a string entry can match nothing. If strict mode is disabled, the comparison will be based on the name.    

---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/ApiReferences/Election%20Class/Election--addCandidate.md)    
