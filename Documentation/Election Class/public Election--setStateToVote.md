## public Election::setStateToVote

### Description    

```php
public $Election -> setStateToVote ( ) : bool
```

Force the election to get back to state 2. See Election::getState.
It is not necessary to use this method. The election knows how to manage its phase changes on its own. But it is a way to clear the cache containing the results of the methods.

If you are on state 1 (candidate registering), it's will close this state and prepare election to get firsts votes.
If you are on state 3. The method result cache will be clear, but not the pairwise. Which will continue to be updated dynamically.
    

### Return value:   

*(bool)* Always True.


---------------------------------------

### Related method(s)      

* [Election::getState](../Election%20Class/public%20Election--getState.md)    
