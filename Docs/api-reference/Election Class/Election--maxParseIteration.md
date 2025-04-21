## public static Election::maxParseIteration

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L20)

### Description    

```php
public static ?int Election::maxParseIteration 
```

Maximum input for each use of Election::parseCandidate && Election::parseVote. Will throw an exception if exceeded.
Null will deactivate this functionality. An integer will set the limit.
    