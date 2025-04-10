## public Election::removeCandidates

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L204)

### Description    

```php
public Election->removeCandidates ( CondorcetPHP\Condorcet\Candidate|array|string $candidates_input ): array
```

Remove candidates from an election.

*Please note: You cannot remove candidates after the first vote. An exception will be thrown.*
    

#### **candidates_input:** *`CondorcetPHP\Condorcet\Candidate|array|string`*   
String corresponding to the candidate's name or CondorcetPHP\Condorcet\Candidate object. Array filled with CondorcetPHP\Condorcet\Candidate objects. Array filled with strings corresponding to the candidate's name.    


### Return value   

*(`array`)* List of removed candidate objects.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateDoesNotExistException``` 
* ```CondorcetPHP\Condorcet\Throwable\VotingHasStartedException``` 

---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidate.md)    
* [Election::getCandidatesList](/Docs/ApiReferences/Election%20Class/public%20Election--getCandidatesList.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/4.Candidates)**    
