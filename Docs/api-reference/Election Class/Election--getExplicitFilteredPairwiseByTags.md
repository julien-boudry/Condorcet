# public Election::getExplicitFilteredPairwiseByTags

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L233)

## Description    

```php
public Election->getExplicitFilteredPairwiseByTags ( array|string $tags [, int|bool $with = 1] ): array
```

Get a pairwise filtered by tags.
    

### **tags:** *`array|string`*   
Tags as string separated by commas or array.    


### **with:** *`int|bool`*   
Minimum number of specified tags that votes must include, or 0 for only votes without any specified tags.    


## Return value   

*(`array`)* Return a Pairwise filtered by tags


---------------------------------------

## Related method(s)      

* [Election::getPairwise](/Docs/api-reference/Election%20Class/Election--getPairwise.md)    
