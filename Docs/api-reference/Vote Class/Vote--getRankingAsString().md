# public Vote::getRankingAsString

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Vote.php#L426)

## Description    

```php
public Vote->getRankingAsString ( [?CondorcetPHP\Condorcet\Election $context = null , bool $displayWeight = true] ): string
```

Get the current ranking as a string format. Optionally with an election context.

## Parameters

### **context:** *`?CondorcetPHP\Condorcet\Election`*   
An election already linked to the Vote.    

### **displayWeight:** *`bool`*   
Include or not the weight symbol and value.    


## Return value   

*(`string`)* String like 'A>D=C>B


---------------------------------------

## Related

* [Vote::getRanking()](/Docs/api-reference/Vote%20Class/Vote--getRanking().md)    
