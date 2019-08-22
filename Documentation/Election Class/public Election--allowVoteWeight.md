## public Election::allowVoteWeight

### Description    

```php
public $Election -> allowVoteWeight ( bool rule = true ) : bool
```

Set the setting and reset all result data.
Then the weight of votes (if specified) will be taken into account when calculating the results. Otherwise all votes will be considered equal.
By default, the voting weight is not activated and all votes are considered equal. 
    

##### **rule:** *bool*   
New rule.    


### Return value:   

Return True


---------------------------------------

### Related method(s)      

* [Election::isVoteWeightAllowed](../Election%20Class/public%20Election--isVoteWeightAllowed.md)    
