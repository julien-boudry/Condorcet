## public Election::getVotesListAsString

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L114)

### Description    

```php
public Election->getVotesListAsString ( [bool $withContext = true] ): string
```

Get registered vote list.
    

#### **withContext:** *`bool`*   
Depending of the implicit ranking rule of the election, will complete or not the ranking. If $withContext is false, ranking are never adapted to the context.    


### Return value:   

*(`string`)* Return a string like :<br>
A > B > C * 3<br>
A = B > C * 6


---------------------------------------

### Related method(s)      

* [Election::parseVotes](/Docs/ApiReferences/Election%20Class/public%20Election--parseVotes.md)    
