## public static Tools\Converters\CondorcetFormat::exportElectionToCondorcetFormat

### Description    

```php
public static Tools\Converters\CondorcetFormat::exportElectionToCondorcetFormat ( CondorcetPHP\Condorcet\Election election [, bool aggregateVotes = true , bool includeNumberOfSeats = true , bool includeTags = true , ?SplFileObject file = null] ): ?string
```

Create a CondorcetFormat file from an Election object.

    

##### **election:** *```CondorcetPHP\Condorcet\Election```*   
Election with data.    


##### **aggregateVotes:** *```bool```*   
If true, will try to reduce number of lines, with quantifier for identical votes.    


##### **includeNumberOfSeats:** *```bool```*   
Add the Number Of Seats parameters to the output.    


##### **includeTags:** *```bool```*   
Add the vote tags information if any. Don't work if $aggregateVotes is true.    


##### **file:** *```?SplFileObject```*   
    


### Return value:   

*(```?string```)* If the file is not provided, it's return a CondorcetFormat as string, else returning null and working directly on the file object (necessary for very large non-aggregated elections, at the risk of memory saturation).

