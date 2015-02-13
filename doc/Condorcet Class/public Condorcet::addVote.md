## public Condorcet::addVote

### Description    

```php
public $Condorcet -> addVote ( mixed vote [, mixed tags] )
```

Add a vote to an election.    


##### **vote:** *mixed*   
String or array representation. Or Condorcet\Vote object. If you not provide yourself Vote object, a new one will be generate for you.     



##### **tags:** *mixed*   
String separated by commas or an array. Will add tags to the vote object for you. But you can too add it yourself to Vote object.    



### Return value:   

The vote object.


---------------------------------------

### Related method(s)      

* [Condorcet::parseVotes](../Condorcet Class/public Condorcet::parseVotes.md)    
* [Condorcet::jsonVotes](../Condorcet Class/public Condorcet::jsonVotes.md)    
* [Condorcet::removeVote](../Condorcet Class/public Condorcet::removeVote.md)    
* [Condorcet::getVotesList](../Condorcet Class/public Condorcet::getVotesList.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Vote Management](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
