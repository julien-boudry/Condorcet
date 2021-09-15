## public Result::getWarning

### Description    

```php
public Result->getWarning ( [?int type = null] ): array
```

From native methods: only Kemeny-Young use it to inform about a conflict during the computation process.
    

##### **type:** *?int*   
Filter on a specific warning type code.    


### Return value:   

*(array)* Warnings provided by the by the method that generated the warning. Empty array if there is not.

