# public Timer\Manager::getHistory

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Timer/Manager.php#L89)

## Description    

```php
public Timer\Manager->getHistory ( ): array
```

Returns the chronological history of all timed operations for this election.
Entries are recorded in completion order (inner/nested operations appear before
their outer container). Nested chronos will naturally overlap in time-range.


## Return value   

*(`array`)* Each entry describes one timed operation:
- `role`        — human-readable label of the operation, or null if unnamed
- `process_in`  — duration in seconds (float) of this specific operation
- `timer_start` — Unix timestamp (microtime) when the operation started
- `timer_end`   — Unix timestamp (microtime) when the operation ended

