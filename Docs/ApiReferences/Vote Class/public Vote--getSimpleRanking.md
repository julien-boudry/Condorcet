## public Vote::getSimpleRanking

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L431)

### Description    

```php
public Vote->getSimpleRanking ( [?CondorcetPHP\Condorcet\Election $context = null , bool $displayWeight = true] ): string
```

Get the current ranking as a string format. Optionally with an election context, see Election::getContextualRanking()
    

#### **context:** *`?CondorcetPHP\Condorcet\Election`*   
An election already linked to the Vote.    


#### **displayWeight:** *`bool`*   
Include or not the weight symbol and value.    


### Return value:   

*(`string`)* String like 'A>D=C>B'


---------------------------------------

### Related method(s)      

* [Vote::getRanking](/Docs/ApiReferences/Vote%20Class/public%20Vote--getRanking.md)    
