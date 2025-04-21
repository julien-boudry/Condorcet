# public static Condorcet::getMethodClass

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Condorcet.php#L147)

## Description    

```php
public static Condorcet::getMethodClass ( string $method ): ?string
```

Return the full class path for a method.

## Parameter

### **method:** *`string`*   
A valid method name.    


## Return value   

*(`?string`)* Return null is method not exist.



## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented``` 

---------------------------------------

## Related method(s)      

* [Condorcet::getAuthMethods](/Docs/api-reference/Condorcet%20Class/Condorcet--getAuthMethods.md)    
