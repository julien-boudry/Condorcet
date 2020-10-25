## public Election::canAddCandidate

### Description    

```php
public $Election -> canAddCandidate ( CondorcetPHP\Condorcet\Candidate|string candidate ) : bool
```

Check if a Candidate is alredeay register. User strict Vote object comparaison, but also string namming comparaison into the election.
    

##### **candidate:** *CondorcetPHP\Condorcet\Candidate|string*   
String or Condorcet/Vote object.    


### Return value:   

*(bool)* True if your Candidate is available. Or False.


---------------------------------------

### Related method(s)      

* [Election::addCandidate](../Election%20Class/public%20Election--addCandidate.md)    
