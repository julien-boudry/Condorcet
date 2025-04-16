## public Election::setStateToVote

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L504)

### Description    

```php
public Election->setStateToVote ( ): true
```

Force the election to get back to state 2.
It is not necessary to use this method. The election knows how to manage its phase changes on its own. But it is a way to clear the cache containing the results of the methods.

If you are on state 1 (candidate registering), it's will close this state and prepare election to get firsts votes.
If you are on state 3. The method result cache will be clear, but not the pairwise. Which will continue to be updated dynamically.
    

### Return value   

*(`true`)* Always True.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\NoCandidatesException``` 
* ```CondorcetPHP\Condorcet\Throwable\ResultRequestedWithoutVotesException``` 

---------------------------------------

### Related method(s)      

* [Election::state](/Docs/api-reference/Election%20Class/Election--state.md)    
