## public Election::removeVotesByTags

### Description    

```php
public $Election -> removeVotesByTags ( mixed tags [, mixed with = true] )
```

Remove Vote from an election using tags.

```php
$condorcet->removeVotesByTags('Charlie') ; // Remove vote(s) with tag Charlie
$condorcet->removeVotesByTags('Charlie', false) ; // Remove votes without tag Charlie
$condorcet->removeVotesByTags('Charlie, Julien', false) ; // Remove votes without tag Charlie AND without tag Julien.
$condorcet->removeVotesByTags(array('Julien','Charlie')) ; // Remove votes with tag Charlie OR with tag Julien.
```    


##### **tags:** *mixed*   
    



##### **with:** *mixed*   
    



### Return value:   

*(array)* List of removed CondorcetPHP\Condorcet\Vote object.


---------------------------------------

### Related method(s)      

* [Election::addVote](../Election%20Class/public%20Election--addVote.md)    
* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    
* [Election::removeVote](../Election%20Class/public%20Election--removeVote.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote management](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-2.-Manage-Vote)**    
