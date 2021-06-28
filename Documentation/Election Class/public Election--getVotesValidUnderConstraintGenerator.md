## public Election::getVotesValidUnderConstraintGenerator

### Description    

```php
public Election->getVotesValidUnderConstraintGenerator ( [mixed tags = null , bool with = true] ) : Generator
```

Same as Election::getVotesList. But Return a PHP generator object.
Usefull if your work on very large election with an external DataHandler, because it's will not using large memory amount.
    

##### **tags:** *mixed*   
Tags list as a string separated by commas or array.    


##### **with:** *bool*   
Get votes with these tags or without.    


### Return value:   

*(Generator)* Populated by each Vote object.


---------------------------------------

### Related method(s)      

* [Election::getVotesListGenerator](../Election%20Class/public%20Election--getVotesListGenerator.md)    
* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    