## public Election::removeCandidates

### Description    

```php
public Election->removeCandidates ( CondorcetPHP\Condorcet\Candidate|array|string $candidates_input ): array
```

Remove candidates from an election.

*Please note: You can't remove candidates after the first vote. An exception will be thrown.*
    

#### **candidates_input:** *```CondorcetPHP\Condorcet\Candidate|array|string```*   
* String matching candidate name
* CondorcetPHP\Condorcet\Candidate object
* Array populated by CondorcetPHP\Condorcet\Candidate
* Array populated by string matching candidate name.    


### Return value:   

*(```array```)* List of removed CondorcetPHP\Condorcet\Candidate object.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException```
* ```CondorcetPHP\Condorcet\Throwable\VotingHasStartedException```

---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/MethodsReferences/Election%20Class/public%20Election--addCandidate.md)    
* [Election::getCandidatesList](/Docs/MethodsReferences/Election%20Class/public%20Election--getCandidatesList.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io#/3.AsPhpLibrary/4.Candidates)**    
