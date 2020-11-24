## public Election::removeCandidates

### Description    

```php
public Election->removeCandidates ( mixed candidates_input ) : array
```

Remove Candidates from an election.

*Please note: You can't remove candidates after the first vote. Exception will be throw.*
    

##### **candidates_input:** *mixed*   
* String matching Candidate Name
* CondorcetPHP\Condorcet\Candidate object
* Array populated by CondorcetPHP\Condorcet\Candidate
* Array populated by string matching Candidate name
    


### Return value:   

*(array)* List of removed CondorcetPHP\Condorcet\Candidate object.


---------------------------------------

### Related method(s)      

* [Election::addCandidate](../Election%20Class/public%20Election--addCandidate.md)    
* [Election::getCandidatesList](../Election%20Class/public%20Election--getCandidatesList.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidate](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
