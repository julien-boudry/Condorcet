# public Election::removeCandidates

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L242)

## Description    

```php
public Election->removeCandidates ( CondorcetPHP\Condorcet\Candidate|array|string $candidates_input ): array
```

Remove candidates from an election.

*Please note: You cannot remove candidates after the first vote. An exception will be thrown.*

## Parameter

### **candidates_input:** *`CondorcetPHP\Condorcet\Candidate|array|string`*   
String corresponding to the candidate's name or CondorcetPHP\Condorcet\Candidate object. Array filled with CondorcetPHP\Condorcet\Candidate objects. Array filled with strings corresponding to the candidate's name.    


## Return value   

*(`array`)* List of removed candidate objects.



## Throws:   

* ```CandidateDoesNotExistException``` 
* ```VotingHasStartedException``` 

---------------------------------------

## Related

* [\CondorcetPHP\Condorcet\Election::getCandidatesList()]()    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/4.Candidates)**    
