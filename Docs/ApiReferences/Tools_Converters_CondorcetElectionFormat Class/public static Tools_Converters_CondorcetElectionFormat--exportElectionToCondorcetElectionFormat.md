## public static Tools\Converters\CondorcetElectionFormat::exportElectionToCondorcetElectionFormat

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CondorcetElectionFormat.php#L28)

### Description    

```php
public static Tools\Converters\CondorcetElectionFormat::exportElectionToCondorcetElectionFormat ( CondorcetPHP\Condorcet\Election $election [, bool $aggregateVotes = true , bool $includeNumberOfSeats = true , bool $includeTags = true , bool $inContext = false , ?SplFileObject $file = null] ): ?string
```

Create a CondorcetElectionFormat file from an Election object.

    

#### **election:** *```CondorcetPHP\Condorcet\Election```*   
Election with data.    


#### **aggregateVotes:** *```bool```*   
If true, will try to reduce number of lines, with quantifier for identical votes.    


#### **includeNumberOfSeats:** *```bool```*   
Add the Number Of Seats parameters to the output.    


#### **includeTags:** *```bool```*   
Add the vote tags information if any. Don't work if $aggregateVotes is true.    


#### **inContext:** *```bool```*   
Non-election candidates will be ignored. If the implicit ranking parameter of the election object is true, the last rank will also be provided to facilitate the reading.    


#### **file:** *```?SplFileObject```*   
If provided, the function will return null and the result will be writing directly to the file instead. _Note that the file cursor is not rewinding_.    


### Return value:   

*(```?string```)* If the file is not provided, it's return a CondorcetElectionFormat as string, else returning null and working directly on the file object (necessary for very large non-aggregated elections, at the risk of memory saturation).

