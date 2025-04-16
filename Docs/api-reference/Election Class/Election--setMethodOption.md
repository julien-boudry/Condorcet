## public Election::setMethodOption

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L258)

### Description    

```php
public Election->setMethodOption ( string $method , string $optionName , BackedEnum|Random\Randomizer|array|string|int|float $optionValue ): static
```

Set an option to a method module and reset his cache for this election object. Be aware that this option applies to all election objects and remains in memory.
    

#### **method:** *`string`*   
Method name or class path.    


#### **optionName:** *`string`*   
Option name.    


#### **optionValue:** *`BackedEnum|Random\Randomizer|array|string|int|float`*   
Option Value.    

---------------------------------------

### Related method(s)      

* [Result::methodOptions](/Docs/api-reference/Result%20Class/Result--methodOptions.md)    
