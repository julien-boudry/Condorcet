## public Election::setMethodOption

### Description    

```php
public Election->setMethodOption ( string $method , string $optionName , BackedEnum|array|string|int $optionValue ): bool
```

Set an option to a method module and reset his cache for this election object. Be aware that this option applies to all election objects and remains in memory.
    

##### **method:** *```string```*   
Method name or class path.    


##### **optionName:** *```string```*   
Option name.    


##### **optionValue:** *```BackedEnum|array|string|int```*   
Option Value.    


### Return value:   

*(```bool```)* True on success. Else False.


---------------------------------------

### Related method(s)      

* [Result::getMethodOptions](../Result%20Class/public%20Result--getMethodOptions.md)    
