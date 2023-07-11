## public Election::parseVotes

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L370)

### Description    

```php
public Election->parseVotes ( string $input [, bool $isFile = false] ): int
```

Import votes from a text source. If any invalid vote is found inside, nothing are registered.
    

#### **input:** *`string`*   
String or valid path to a text file.    


#### **isFile:** *`bool`*   
If true, the input is evalatued as path to text file.    


### Return value:   

*(`int`)* Count of the new registered vote.


---------------------------------------

### Related method(s)      

* [Election::addVote](/Docs/ApiReferences/Election%20Class/public%20Election--addVote.md)    
* [Election::parseCandidates](/Docs/ApiReferences/Election%20Class/public%20Election--parseCandidates.md)    
* [Election::parseVotesWithoutFail](/Docs/ApiReferences/Election%20Class/public%20Election--parseVotesWithoutFail.md)    
* [Election::addVotesFromJson](/Docs/ApiReferences/Election%20Class/public%20Election--addVotesFromJson.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
