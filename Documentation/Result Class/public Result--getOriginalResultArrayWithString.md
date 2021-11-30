## public Result::getOriginalResultArrayWithString

### Description    

```php
public Result->getOriginalResultArrayWithString ( ): array
```

Get result as an array
    

### Return value:   

*(```array```)* Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.
Candidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.

This control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.


---------------------------------------

### Related method(s)      

* [Result::getResultAsArray](../Result%20Class/public%20Result--getResultAsArray.md)    
* [Result::getResultAsString](../Result%20Class/public%20Result--getResultAsString.md)    
