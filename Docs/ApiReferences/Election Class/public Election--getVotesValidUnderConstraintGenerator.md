## public Election::getVotesValidUnderConstraintGenerator

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L132)

### Description    

```php
public Election->getVotesValidUnderConstraintGenerator ( [array|string|null $tags = null , bool $with = true] ): Generator
```

Same as Election::getVotesList, filter out vote invalid under constraint. But Return a PHP generator object.
Usefull if your work on very large election with an external DataHandler, because it's will not using large memory amount.
    

#### **tags:** *`array|string|null`*   
Tags list as a string separated by commas or array.    


#### **with:** *`bool`*   
Get votes with these tags or without.    


### Return value:   

*(`Generator`)* Populated by each Vote object.


---------------------------------------

### Related method(s)      

* [Election::getVotesListGenerator](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesListGenerator.md)    
* [Election::getVotesList](/Docs/ApiReferences/Election%20Class/public%20Election--getVotesList.md)    
