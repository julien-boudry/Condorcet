## public Election::parseCandidates

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L276)

### Description    

```php
public Election->parseCandidates ( string $input [, bool $isFile = false] ): array
```

Import candidates from a text source.
    

#### **input:** *`string`*   
String or valid path to a text file.    


#### **isFile:** *`bool`*   
If true, the input is evaluated as a path to a text file.    


### Return value   

*(`array`)* List of newly registered candidate objects. Count to check if all candidates were correctly registered.



### Throws:   

* ```CandidateExistsException``` 
* ```VoteMaxNumberReachedException``` 

---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/api-reference/Election%20Class/Election--addCandidate.md)    
* [Election::addCandidatesFromJson](/Docs/api-reference/Election%20Class/Election--addCandidatesFromJson.md)    
* [Election::parseVotes](/Docs/api-reference/Election%20Class/Election--parseVotes.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/4.Candidates)**    
