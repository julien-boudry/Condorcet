## public Election::isRegisteredCandidate

### Description    

```php
public $Election -> isRegisteredCandidate ( mixed candidate [, bool strictMode = true] ) : bool
```

Check if a candidate is already taking part in the election.
    

##### **candidate:** *mixed*   
Candidate object or candidate string name. String name can working only if the strict mode is active.
    


##### **strictMode:** *bool*   
Search comparaison mode. In strict mode, candidate object are compared strictly and a string input can't match anything.
If strict mode is false, the comparaison will be based on name.
    


### Return value:   

*(bool)* True / False


---------------------------------------

### Related method(s)      

* [Election::addCandidate](../Election%20Class/public%20Election--addCandidate.md)    
