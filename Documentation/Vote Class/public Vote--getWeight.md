## public Vote::getWeight

### Description    

```php
public $Vote -> getWeight ( [?CondorcetPHP\Condorcet\Election context = null] ) : int
```

Get the vote weight. The vote weight capacity must be active at the election level for producing effect on the result.
    

##### **context:** *?CondorcetPHP\Condorcet\Election*   
An election already linked to the Vote.    


### Return value:   

*(int)* Weight. Default weight is 1.


---------------------------------------

### Related method(s)      

* [Vote::setWeight](../Vote%20Class/public%20Vote--setWeight.md)    
