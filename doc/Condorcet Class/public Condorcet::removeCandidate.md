## public Condorcet::removeCandidate

### Description    

```php
public $Condorcet -> removeCandidate ( mixed candidate )
```

Remove Candidate from an election.

*Please note: You can't remove candidate after the first vote. Exception will be throw.*    


##### **candidate:** *mixed*   
* String matching Candidate Name
* Condorcet\Candidate object
* Array populated by Condorcet\Candidate
* Array populated by string matching Candidate name    



### Return value:   

*(array)* List of removed Condorcet\Candidate object.


---------------------------------------

### Related method(s)      

* [Condorcet::AddCandidate](../Condorcet Class/public Condorcet::AddCandidate.md)    
* [Condorcet::getCandidatesList](../Condorcet Class/public Condorcet::getCandidatesList.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidate](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
