## public static Condorcet::setMaxVoteNumber

### Description    

```php
public static Condorcet::setMaxVoteNumber ( mixed value )
```

Add a limitation on Condorcet::addVote and related methods. You can't add new vote y the number of registered vote is equall ou superior of this limit.    


##### **value:** *mixed*   
Null will desactivate this functionnality. An interger will fix the limit.    



### Return value:   

*(int or null)* The new limit.


---------------------------------------

### Related method(s)      

* [static Condorcet::setMaxParseIteration](../Condorcet Class/public static Condorcet--setMaxParseIteration.md)    
