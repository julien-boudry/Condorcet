## public Election::canAddCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L181)

### Description    

```php
public Election->canAddCandidate ( CondorcetPHP\Condorcet\Candidate|string $candidate ): bool
```

Check if a candidate is already registered. Equivalent of `!$election->hasCandidate($candidate, false)`.
    

#### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string`*   
String or Condorcet/Vote object.    


### Return value   

*(`bool`)* True if your candidate is available, false otherwise.


---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/api-reference/Election%20Class/Election--addCandidate.md)    
* [Election::hasCandidate](/Docs/api-reference/Election%20Class/Election--hasCandidate.md)    
