## public static CondorcetUtil::format

### Description    

```php
public static CondorcetUtil::format ( mixed input [, bool out = true, bool convertObject = true] ) : ?mixed
```

Provide pretty re-formatting, human compliant, of all Condorcet PHP object or result set.
Can be use for improve var_dump, or just to get more simple data output.    


##### **input:** *mixed*   
All datatype. Like classical var_dump    



##### **out:** *bool*   
If true, will print the result on scrren. like var_dump() function. Else, just return it.    



##### **convertObject:** *bool*   
If true. Will convert Candidate objects into string representation of their name.    



### Return value:   

New formatted data.

