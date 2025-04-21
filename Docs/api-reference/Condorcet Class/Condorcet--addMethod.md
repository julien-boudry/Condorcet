# public static Condorcet::addMethod

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Condorcet.php#L192)

## Description    

```php
public static Condorcet::addMethod ( string $methodClass ): bool
```

If you create your own Condorcet Algo. You will need it !

## Parameter

### **methodClass:** *`string`*   
The class name implementing your method. The class name includes the namespace it was declared in (e.g. Foo\Bar).    


## Return value   

*(`bool`)* True on Success. False on failure.


---------------------------------------

## Related method(s)      

* [Condorcet::isAuthMethod](/Docs/api-reference/Condorcet%20Class/Condorcet--isAuthMethod.md)    
* [Condorcet::getMethodClass](/Docs/api-reference/Condorcet%20Class/Condorcet--getMethodClass.md)    
