## public Election::addCandidatesFromJson

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L222)

### Description    

```php
public Election->addCandidatesFromJson ( string $input ): array
```

Import candidate from a JSON source.
    

#### **input:** *`string`*   
JSON string input.    


### Return value:   

*(`array`)* List of newly registered candidate object.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateExistsException```

---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidate.md)    
* [Election::parseCandidates](/Docs/ApiReferences/Election%20Class/public%20Election--parseCandidates.md)    
* [Election::addVotesFromJson](/Docs/ApiReferences/Election%20Class/public%20Election--addVotesFromJson.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io#/3.AsPhpLibrary/4.Candidates)**    
