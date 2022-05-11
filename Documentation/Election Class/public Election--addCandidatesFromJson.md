## public Election::addCandidatesFromJson

### Description    

```php
public Election->addCandidatesFromJson ( string $input ): array
```

Import candidate from a JSON source.
    

##### **input:** *```string```*   
JSON string input.    


### Return value:   

*(```array```)* List of newly registered candidate object.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateExistsException```

---------------------------------------

### Related method(s)      

* [Election::addCandidate](../Election%20Class/public%20Election--addCandidate.md)    
* [Election::parseCandidates](../Election%20Class/public%20Election--parseCandidates.md)    
* [Election::addVotesFromJson](../Election%20Class/public%20Election--addVotesFromJson.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidates](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
