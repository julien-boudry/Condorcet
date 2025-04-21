## public Election::addCandidatesFromJson

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/CandidatesProcess.php#L240)

### Description    

```php
public Election->addCandidatesFromJson ( string $input ): array
```

Import candidates from a JSON source.
    

#### **input:** *`string`*   
JSON string.    


### Return value   

*(`array`)* List of newly registered candidate objects.



### Throws:   

* ```CandidateExistsException``` 

---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/api-reference/Election%20Class/Election--addCandidate.md)    
* [Election::parseCandidates](/Docs/api-reference/Election%20Class/Election--parseCandidates.md)    
* [Election::addVotesFromJson](/Docs/api-reference/Election%20Class/Election--addVotesFromJson.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/4.Candidates)**    
