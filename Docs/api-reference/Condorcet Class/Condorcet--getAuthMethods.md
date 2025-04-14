## public static Condorcet::getAuthMethods

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Condorcet.php#L108)

### Description    

```php
public static Condorcet::getAuthMethods ( [bool $basic = false , bool $withNonDeterministicMethods = true] ): array
```

Get a list of supported algorithm.
    

#### **basic:** *`bool`*   
Include or not the natural Condorcet base algorithm.    


#### **withNonDeterministicMethods:** *`bool`*   
Include or not non deterministic methods.    


### Return value   

*(`array`)* Populated by method string name. You can use it on getResult ... and others methods.


---------------------------------------

### Related method(s)      

* [Condorcet::isAuthMethod](/Docs/api-reference/Condorcet%20Class/Condorcet--isAuthMethod.md)    
* [Condorcet::getMethodClass](/Docs/api-reference/Condorcet%20Class/Condorcet--getMethodClass.md)    
