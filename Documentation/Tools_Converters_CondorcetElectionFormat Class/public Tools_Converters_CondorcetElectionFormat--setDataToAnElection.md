## public Tools\Converters\CondorcetElectionFormat::setDataToAnElection

### Description    

```php
public Tools\Converters\CondorcetElectionFormat->setDataToAnElection ( [CondorcetPHP\Condorcet\Election $election = new CondorcetPHP\Condorcet\Election , ?Closure $callBack = null] ): CondorcetPHP\Condorcet\Election
```

Add the data to an election object
    

##### **election:** *```CondorcetPHP\Condorcet\Election```*   
Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.    


##### **callBack:** *```?Closure```*   
Callback function to execute after each registered vote.    


### Return value:   

*(```CondorcetPHP\Condorcet\Election```)* The election object


---------------------------------------

### Related method(s)      

* [Tools\DavidHillFormat::setDataToAnElection](../Tools\DavidHillFormat%20Class/public%20Tools\DavidHillFormat--setDataToAnElection.md)    
* [Tools\DebianFormat::setDataToAnElection](../Tools\DebianFormat%20Class/public%20Tools\DebianFormat--setDataToAnElection.md)    
