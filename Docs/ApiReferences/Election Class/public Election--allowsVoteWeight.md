## public Election::allowsVoteWeight

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L289)

### Description    

```php
public Election->allowsVoteWeight ( [bool $rule = true] ): bool
```

Set the setting and reset all result data.
Then the weight of votes (if specified) will be taken into account when calculating the results. Otherwise all votes will be considered equal.
By default, the voting weight is not activated and all votes are considered equal.
    

#### **rule:** *`bool`*   
New rule.    


### Return value:   

*(`bool`)* Return True


---------------------------------------

### Related method(s)      

* [Election::isVoteWeightAllowed](/Docs/ApiReferences/Election%20Class/public%20Election--isVoteWeightAllowed.md)    
