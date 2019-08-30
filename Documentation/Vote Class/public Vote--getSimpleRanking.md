## public Vote::getSimpleRanking

### Description    

```php
public $Vote -> getSimpleRanking ( [\CondorcetPHP\Condorcet\Election context = null] ) : string
```

Get the current ranking as a string format. Optionally with an election context, see Election::getContextualRanking()
    

##### **context:** *\CondorcetPHP\Condorcet\Election*   
An election already linked to the Vote.    


### Return value:   

*(string)* String like 'A>D=C>B'


---------------------------------------

### Related method(s)      

* [Vote::getRanking](../Vote%20Class/public%20Vote--getRanking.md)    
