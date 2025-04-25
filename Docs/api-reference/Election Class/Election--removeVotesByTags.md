# public Election::removeVotesByTags

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/VotesProcess.php#L335)

## Description    

```php
public Election->removeVotesByTags ( array|string $tags [, bool $with = true] ): array
```

Remove Vote from an election using tags.

```php
\$election->removeVotesByTags('Charlie') ; // Remove vote(s) with tag Charlie
\$election->removeVotesByTags('Charlie', false) ; // Remove votes without tag Charlie
\$election->removeVotesByTags('Charlie, Julien', false) ; // Remove votes without tag Charlie AND without tag Julien.
\$election->removeVotesByTags(array('Julien','Charlie')) ; // Remove votes with tag Charlie OR with tag Julien.
```

## Parameters

### **tags:** *`array|string`*   
Tags as string separated by commas or array.    

### **with:** *`bool`*   
Votes with these tags or without.    


## Return value   

*(`array`)* List of removed CondorcetPHP\Condorcet\Vote object.


---------------------------------------

## Related method(s)      

* [Election::addVote](/Docs/api-reference/Election%20Class/Election--addVote.md)    
* [Election::getVotesList](/Docs/api-reference/Election%20Class/Election--getVotesList.md)    
* [Election::removeVote](/Docs/api-reference/Election%20Class/Election--removeVote.md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/3.AsPhpLibrary/5.Votes/2.VotesTags)**    
