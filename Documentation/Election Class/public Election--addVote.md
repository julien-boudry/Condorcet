## public Election::addVote

### Description    

```php
public $Election -> addVote ( mixed vote [, mixed tags = null] ) : \CondorcetPHP\Condorcet\Vote
```

Add a vote to an election.    


##### **vote:** *mixed*   
String or array representation. Or CondorcetPHP\Condorcet\Vote object. If you not provide yourself Vote object, a new one will be generate for you.     



##### **tags:** *mixed*   
String separated by commas or an array. Will add tags to the vote object for you. But you can too add it yourself to Vote object.    



### Return value:   

The vote object.


---------------------------------------

### Related method(s)      

* [Election::parseVotes](../Election%20Class/public%20Election--parseVotes.md)    
* [Election::addVotesFromJson](../Election%20Class/public%20Election--addVotesFromJson.md)    
* [Election::removeVote](../Election%20Class/public%20Election--removeVote.md)    
* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote Management](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
