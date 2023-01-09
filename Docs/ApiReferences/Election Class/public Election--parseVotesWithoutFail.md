## public Election::parseVotesWithoutFail

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L393)

### Description    

```php
public Election->parseVotesWithoutFail ( SplFileInfo|string $input [, bool $isFile = false , ?Closure $callBack = null] ): int
```

Similar to parseVote method. But will ignore invalid line. This method is also far less greedy in memory and must be prefered for very large file input. And to combine with the use of an external data handler.
    

#### **input:** *```SplFileInfo|string```*   
String, valid path to a text file or an object SplFileInfo or extending it like SplFileObject.    


#### **isFile:** *```bool```*   
If true, the string input is evalatued as path to text file.    


#### **callBack:** *```?Closure```*   
Callback function to execute after each valid line, before vote registration.    


### Return value:   

*(```int```)* Number of invalid records into input (except empty lines). It's not an invalid votes count. Check Election::countVotes if you want to be sure.


---------------------------------------

### Related method(s)      

* [Election::addVote](/Docs/ApiReferences/Election%20Class/public%20Election--addVote.md)    
* [Election::parseCandidates](/Docs/ApiReferences/Election%20Class/public%20Election--parseCandidates.md)    
* [Election::parseVotes](/Docs/ApiReferences/Election%20Class/public%20Election--parseVotes.md)    
* [Election::addVotesFromJson](/Docs/ApiReferences/Election%20Class/public%20Election--addVotesFromJson.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io#/3.AsPhpLibrary/5.Votes/1.AddVotes)**    
