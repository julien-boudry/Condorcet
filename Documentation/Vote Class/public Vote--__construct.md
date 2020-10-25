## public Vote::__construct

### Description    

```php
public $Vote -> __construct ( array|string ranking [, array|string|null tags = null , ?float ownTimestamp = null , ?CondorcetPHP\Condorcet\Election electionContext = null] )
```

Build a vote object.
    

##### **ranking:** *array|string*   
Equivalent to Vote::setRanking method.    


##### **tags:** *array|string|null*   
Equivalent to Vote::addTags method.    


##### **ownTimestamp:** *?float*   
Set your own timestamp metadata on Ranking.    


##### **electionContext:** *?CondorcetPHP\Condorcet\Election*   
Try to convert directly your candidates from sting input" to Candidate object of one election.    

---------------------------------------

### Related method(s)      

* [Vote::setRanking](../Vote%20Class/public%20Vote--setRanking.md)    
* [Vote::addTags](../Vote%20Class/public%20Vote--addTags.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Add Vote](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
