## public Election::isRegisteredCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L90)

### Description    

```php
public Election->isRegisteredCandidate ( CondorcetPHP\Condorcet\Candidate|string $candidate [, bool $strictMode = true] ): bool
```

Check if a candidate is already taking part in the election.
    

#### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string`*   
Candidate object or candidate string name. String name works only if the strict mode is false.    


#### **strictMode:** *`bool`*   
Search comparison mode. In strict mode, candidate objects are compared strictly and a string input can't match anything.
If strict mode is false, the comparison will be based on name.    


### Return value:   

*(`bool`)* True / False


---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidate.md)    
