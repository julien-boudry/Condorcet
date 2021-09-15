## public Election::parseVotesWithoutFail

### Description    

```php
public Election->parseVotesWithoutFail ( string input [, bool isFile = false , ?Closure callBack = null] ): int
```

Similar to parseVote method. But will ignore invalid line. This method is also far less greedy in memory and must be prefered for very large file input. And to combine with the use of an external data handler.
    

##### **input:** *string*   
String or valid path to a text file.    


##### **isFile:** *bool*   
If true, the input is evalatued as path to text file.    


##### **callBack:** *?Closure*   
Callback function to execute after each registered vote.    


### Return value:   

*(int)* Number of invalid records into input (except empty lines). It's not invalid votes count. Check Election::countVotes if you want to be sure.


---------------------------------------

### Related method(s)      

* [Election::addVote](../Election%20Class/public%20Election--addVote.md)    
* [Election::parseCandidates](../Election%20Class/public%20Election--parseCandidates.md)    
* [Election::parseVotes](../Election%20Class/public%20Election--parseVotes.md)    
* [Election::addVotesFromJson](../Election%20Class/public%20Election--addVotesFromJson.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Add Vote](https://github.com/julien-boudry/Condorcet/wiki/II-%23-B.-Vote-management-%23-1.-Add-Vote)**    
