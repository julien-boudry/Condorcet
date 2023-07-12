## public Election::removeExternalDataHandler

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L426)

### Description    

```php
public Election->removeExternalDataHandler ( ): bool
```

Remove an external driver to store vote on very large election. And import his data into classical memory.
    

### Return value:   

*(`bool`)* True if success. Else throw an Exception.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\DataHandlerException```

---------------------------------------

### Related method(s)      

* [Election::setExternalDataHandler](/Docs/ApiReferences/Election%20Class/public%20Election--setExternalDataHandler.md)    
