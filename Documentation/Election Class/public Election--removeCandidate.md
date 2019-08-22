## public Election::removeCandidate

### Description    

```php
public $Election -> removeCandidate ( mixed candidate ) : array
```

Remove Candidate from an election.   

*Please note: You can't remove candidate after the first vote. Exception will be throw.*
    

##### **candidate:** *mixed*   
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
