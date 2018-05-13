## public Election::removeVote

### Description    

```php
public $Election -> removeVote ( mixed in [, mixed with = true] )
```

Remove Vote from an election.

```php
$condorcet->removeVote('Charlie') ; // Remove vote(s) with tag Charlie
$condorcet->removeVote('Charlie', false) ; // Remove votes without tag Charlie
$condorcet->removeVote('Charlie, Julien', false) ; // Remove votes without tag Charlie AND without tag Julien.
$condorcet->removeVote(array('Julien','Charlie')) ; // Remove votes with tag Charlie OR with tag Julien.
$condorcet->removeVote($myVoteObject) ; // Remove a specific registered Vote.
```    


##### **in:** *mixed*   
    



##### **with:** *mixed*   
    



### Return value:   

*(array)* List of removed Condorcet\Vote object.


---------------------------------------

### Related method(s)      

* [Election::addVote](../Election%20Class/public%20Election--addVote.md)    
* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote management](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-2.-Manage-Vote)**    
