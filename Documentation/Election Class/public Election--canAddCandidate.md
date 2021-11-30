## public Election::canAddCandidate

### Description    

```php
public Election->canAddCandidate ( CondorcetPHP\Condorcet\Candidate|string candidate ): bool
```

Check if a candidate is already registered. Uses strict Vote object comparison, but also string naming comparison in the election.
    

##### **candidate:** *```CondorcetPHP\Condorcet\Candidate|string```*   
String or Condorcet/Vote object.    


### Return value:   

*(```bool```)* True if your candidate is available, false otherwise.


---------------------------------------

### Related method(s)      

* [Election::addCandidate](../Election%20Class/public%20Election--addCandidate.md)    
