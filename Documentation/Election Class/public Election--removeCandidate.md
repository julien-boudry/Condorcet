## public Election::removeCandidate

### Description    

```php
public $Election -> removeCandidate ( mixed candidate )
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

* [Election::AddCandidate](../Election Class/public Election--AddCandidate.md)    
* [Election::getCandidatesList](../Election Class/public Election--getCandidatesList.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidate](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
