## public Election::setMethodOption

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L218)

### Description    

```php
public Election->setMethodOption ( string $method , string $optionName , BackedEnum|Random\Randomizer|array|string|int|float $optionValue ): bool
```

Set an option to a method module and reset his cache for this election object. Be aware that this option applies to all election objects and remains in memory.
    

#### **method:** *`string`*   
Method name or class path.    


#### **optionName:** *`string`*   
Option name.    


#### **optionValue:** *`BackedEnum|Random\Randomizer|array|string|int|float`*   
Option Value.    


### Return value:   

*(`bool`)* True on success. Else False.


---------------------------------------

### Related method(s)      

* [Result::getMethodOptions](/Docs/ApiReferences/Result%20Class/public%20Result--getMethodOptions.md)    
