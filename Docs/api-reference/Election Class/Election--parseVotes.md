# public Election::parseVotes

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L416)

## Description    

```php
public Election->parseVotes ( string $input [, bool $isFile = false] ): int
```

Import votes from a text source. If any invalid vote is found inside, nothing is registered.

## Parameters

### **input:** *`string`*   
String or valid path to a text file.    

### **isFile:** *`bool`*   
If true, the input is evaluated as path to text file.    


## Return value   

*(`int`)* Count of the newly registered votes.


---------------------------------------

## Related method(s)      

* [Election::addVote](/Docs/api-reference/Election%20Class/Election--addVote.md)    
* [Election::parseCandidates](/Docs/api-reference/Election%20Class/Election--parseCandidates.md)    
* [Election::parseVotesSafe](/Docs/api-reference/Election%20Class/Election--parseVotesSafe.md)    
* [Election::addVotesFromJson](/Docs/api-reference/Election%20Class/Election--addVotesFromJson.md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
