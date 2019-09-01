## public Election::getVotesListGenerator

### Description    

```php
public $Election -> getVotesListGenerator ( [mixed tags = null , bool with = true] ) : Generator
```

Same as Election::getVotesList. But Return a PHP generator object.
Usefull if your work on very large election with an external DataHandler, because it's will not using large memory amount.
    

##### **tags:** *mixed*   
    


##### **with:** *bool*   
    


### Return value:   

*(Generator)* Populated by each Vote object.


---------------------------------------

### Related method(s)      

* [Election::getVotesList](../Election%20Class/public%20Election--getVotesList.md)    
