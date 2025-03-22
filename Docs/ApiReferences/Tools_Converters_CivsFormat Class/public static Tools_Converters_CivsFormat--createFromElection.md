## public static Tools\Converters\CivsFormat::createFromElection

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CivsFormat.php#L29)

### Description    

```php
public static Tools\Converters\CivsFormat::createFromElection ( CondorcetPHP\Condorcet\Election $election [, ?SplFileObject $file = null] ): string|true
```

Create a CondorcetElectionFormat file from an Election object.
    

#### **election:** *`CondorcetPHP\Condorcet\Election`*   
Election with data.    


#### **file:** *`?SplFileObject`*   
If provided, the function will return null and the result will be writing directly to the file instead. _Note that the file cursor is not rewinding_.    


### Return value:   

*(`string|true`)* If the file is not provided, it's return a CondorcetElectionFormat as string, else returning null and working directly on the file object (necessary for very large non-aggregated elections, at the risk of memory saturation).

