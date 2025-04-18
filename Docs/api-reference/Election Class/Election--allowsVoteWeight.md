## public Election::allowsVoteWeight

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L342)

### Description    

```php
public Election->allowsVoteWeight ( [bool $rule = true] ): static
```

Set the setting and reset all result data.
Then the weight of votes (if specified) will be taken into account when calculating the results. Otherwise all votes will be considered equal.
By default, the voting weight is not activated and all votes are considered equal.
    

#### **rule:** *`bool`*   
New rule.    


### Return value   

*(`static`)* Return True


---------------------------------------

### Related method(s)      

* [Election::isVoteWeightAllowed](/Docs/api-reference/Election%20Class/Election--isVoteWeightAllowed.md)    
