## public Election::parseCandidates

### Description    

```php
public $Election -> parseCandidates ( string input , bool isFile ) : array
```

Import candidate from a text source.
    

##### **input:** *string*   
String or valid path to a text file.    


##### **isFile:** *bool*   
If true, the input is evalatued as path to text file.    


### Return value:   

*(array)* List of new registered candidate object. Count it for checking if all candidates have been correctly registered.


---------------------------------------

### Related method(s)      

* [Election::addCandidate](../Election%20Class/public%20Election--addCandidate.md)    
* [Election::addCandidatesFromJson](../Election%20Class/public%20Election--addCandidatesFromJson.md)    
* [Election::parseVotes](../Election%20Class/public%20Election--parseVotes.md)    

---------------------------------------

### Examples and explanation

* **[Manual - Manage Candidates](https://github.com/julien-boudry/Condorcet/wiki/II-%23-A.-Create-an-Election-%23-2.-Create-Candidates)**    
