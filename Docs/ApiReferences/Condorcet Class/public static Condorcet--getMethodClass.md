## public static Condorcet::getMethodClass

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Condorcet.php#L150)

### Description    

```php
public static Condorcet::getMethodClass ( string $method ): ?string
```

Return the full class path for a method.
    

#### **method:** *`string`*   
A valid method name.    


### Return value   

*(`?string`)* Return null is method not exist.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\VotingMethodIsNotImplemented``` 

---------------------------------------

### Related method(s)      

* [Condorcet::getAuthMethods](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--getAuthMethods.md)    
