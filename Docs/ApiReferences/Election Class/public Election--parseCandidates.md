## public Election::parseCandidates

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L255)

### Description    

```php
public Election->parseCandidates ( string $input [, bool $isFile = false] ): array
```

Import candidate from a text source.
    

#### **input:** *`string`*   
String or valid path to a text file.    


#### **isFile:** *`bool`*   
If true, the input is evaluated as path to a text file.    


### Return value:   

*(`array`)* List of newly registered candidate object. Count it for checking if all candidates have been correctly registered.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateExistsException```
* ```CondorcetPHP\Condorcet\Throwable\VoteMaxNumberReachedException```

---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidate.md)    
* [Election::addCandidatesFromJson](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidatesFromJson.md)    
* [Election::parseVotes](/Docs/ApiReferences/Election%20Class/public%20Election--parseVotes.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/4.Candidates)**    
