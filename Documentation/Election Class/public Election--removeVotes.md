## public Election::removeVotes

### Description    

```php
public $Election -> removeVotes ( CondorcetPHP\Condorcet\Vote votes_input ) : array
```

Remove Votes from an election.   

```php
$condorcet->removeVotes('Charlie') ; // Remove vote(s) with tag Charlie
$condorcet->removeVotes('Charlie', false) ; // Remove votes without tag Charlie
$condorcet->removeVotes('Charlie, Julien', false) ; // Remove votes without tag Charlie AND without tag Julien.
$condorcet->removeVotes(array('Julien','Charlie')) ; // Remove votes with tag Charlie OR with tag Julien.
$condorcet->removeVotes($myVoteObject) ; // Remove a specific registered Vote.
```
    

##### **votes_input:** *CondorcetPHP\Condorcet\Vote*   
    


### Return value:   

*(array)* List of removed CondorcetPHP\Condorcet\Vote object.


---------------------------------------

### Related method(s)      

* [Election::addVote](../Election%20Class/public%20Election--addVote.md)    
* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    
* [Election::removeVotesByTags](../Election%20Class/public%20Election--removeVotesByTags.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote management](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-2.-Manage-Vote)**    
