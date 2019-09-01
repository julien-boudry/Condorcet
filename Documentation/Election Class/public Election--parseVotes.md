## public Election::parseVotes

### Description    

```php
public $Election -> parseVotes ( string input [, bool isFile = false] ) : int
```

Import votes from a text source.
    

##### **input:** *string*   
String or valid path to a text file.    


##### **isFile:** *bool*   
If true, the input is evalatued as path to text file.    


### Return value:   

*(int)* Count of the new registered vote.


---------------------------------------

### Related method(s)      

* [Election::addVote](../Election%20Class/public%20Election--addVote.md)    
* [Election::parseCandidates](../Election%20Class/public%20Election--parseCandidates.md)    
* [Election::addVotesFromJson](../Election%20Class/public%20Election--addVotesFromJson.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Add Vote](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
