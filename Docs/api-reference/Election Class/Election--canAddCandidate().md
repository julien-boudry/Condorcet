# public Election::canAddCandidate

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L176)

## Description    

```php
public Election->canAddCandidate ( CondorcetPHP\Condorcet\Candidate|string $candidate ): bool
```

Check if a candidate is already registered. Equivalent of `!$election->hasCandidate($candidate, false)`.

## Parameter

### **candidate:** *`CondorcetPHP\Condorcet\Candidate|string`*   
String or Condorcet/Vote object.    


## Return value   

*(`bool`)* True if your candidate is available, false otherwise.


---------------------------------------

## Related

* [Election::addCandidate()](/Docs/api-reference/Election%20Class/Election--addCandidate().md)    
* [Election::hasCandidate()](/Docs/api-reference/Election%20Class/Election--hasCandidate().md)    
