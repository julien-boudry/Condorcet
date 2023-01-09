## public Election::canAddCandidate

### Description    

```php
public Election->canAddCandidate ( CondorcetPHP\Condorcet\Candidate|string $candidate ): bool
```

Check if a candidate is already registered. Equivalent of `!$election->isRegisteredCandidate($candidate, false)`.
    

#### **candidate:** *```CondorcetPHP\Condorcet\Candidate|string```*   
String or Condorcet/Vote object.    


### Return value:   

*(```bool```)* True if your candidate is available, false otherwise.


---------------------------------------

### Related method(s)      

* [Election::addCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--addCandidate.md)    
* [Election::isRegisteredCandidate](/Docs/ApiReferences/Election%20Class/public%20Election--isRegisteredCandidate.md)    
