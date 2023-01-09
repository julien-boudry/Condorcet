## public Result::getOriginalResultAsString

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Result.php#L182)

### Description    

```php
public Result->getOriginalResultAsString ( ): string
```

Get immutable result as a string
    

### Return value:   

*(`string`)* Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.
Candidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.

This control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.


---------------------------------------

### Related method(s)      

* [Result::getResultAsArray](/Docs/ApiReferences/Result%20Class/public%20Result--getResultAsArray.md)    
* [Result::getResultAsString](/Docs/ApiReferences/Result%20Class/public%20Result--getResultAsString.md)    
* [Result::getOriginalResultArrayWithString](/Docs/ApiReferences/Result%20Class/public%20Result--getOriginalResultArrayWithString.md)    
