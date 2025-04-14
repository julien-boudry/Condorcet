## public Election::parseVotesSafe

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L455)

### Description    

```php
public Election->parseVotesSafe ( SplFileInfo|string $input [, bool $isFile = false , ?Closure $callBack = null] ): int
```

Similar to parseVote method. But will ignore invalid lines. This method is also far less greedy in memory and should be preferred for very large file inputs. Best used in combination with an external data handler.
    

#### **input:** *`SplFileInfo|string`*   
String, valid path to a text file or an object SplFileInfo or extending it like SplFileObject.    


#### **isFile:** *`bool`*   
If true, the string input is evaluated as path to text file.    


#### **callBack:** *`?Closure`*   
Callback function to execute after each valid line, before vote registration.    


### Return value   

*(`int`)* Number of invalid records in input (except empty lines). It's not an invalid votes count. Check Election::countVotes if you want to be sure.


---------------------------------------

### Related method(s)      

* [Election::addVote](/Docs/ApiReferences/Election%20Class/Election--addVote.md)    
* [Election::parseCandidates](/Docs/ApiReferences/Election%20Class/Election--parseCandidates.md)    
* [Election::parseVotes](/Docs/ApiReferences/Election%20Class/Election--parseVotes.md)    
* [Election::addVotesFromJson](/Docs/ApiReferences/Election%20Class/Election--addVotesFromJson.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
