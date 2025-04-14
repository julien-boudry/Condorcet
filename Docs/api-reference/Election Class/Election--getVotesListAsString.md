## public Election::getVotesListAsString

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L129)

### Description    

```php
public Election->getVotesListAsString ( [bool $withContext = true] ): string
```

Get registered votes list as string.
    

#### **withContext:** *`bool`*   
Depending on the implicit ranking rule of the election, will complete or not the ranking. If $withContext is false, rankings are never adapted to the context.    


### Return value   

*(`string`)* Return a string like:
A > B > C * 3
A = B > C * 6


---------------------------------------

### Related method(s)      

* [Election::parseVotes](/Docs/api-reference/Election%20Class/Election--parseVotes.md)    
