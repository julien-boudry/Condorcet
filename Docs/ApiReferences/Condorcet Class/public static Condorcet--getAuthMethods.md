## public static Condorcet::getAuthMethods

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Condorcet.php#L94)

### Description    

```php
public static Condorcet::getAuthMethods ( [bool $basic = false , bool $withNonDeterministicMethods = true] ): array
```

Get a list of supported algorithm.
    

#### **basic:** *`bool`*   
Include or not the natural Condorcet base algorithm.    


#### **withNonDeterministicMethods:** *`bool`*   
Include or not non deterministic methods.    


### Return value:   

*(`array`)* Populated by method string name. You can use it on getResult ... and others methods.


---------------------------------------

### Related method(s)      

* [static Condorcet::isAuthMethod](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--isAuthMethod.md)    
* [static Condorcet::getMethodClass](/Docs/ApiReferences/Condorcet%20Class/public%20static%20Condorcet--getMethodClass.md)    
