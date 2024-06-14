## public Tools\Converters\CEF\CondorcetElectionFormat::setDataToAnElection

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CEF/CondorcetElectionFormat.php#L161)

### Description    

```php
public Tools\Converters\CEF\CondorcetElectionFormat->setDataToAnElection ( [CondorcetPHP\Condorcet\Election $election = new CondorcetPHP\Condorcet\Election , ?Closure $callBack = null] ): CondorcetPHP\Condorcet\Election
```

Add the data to an election object
    

#### **election:** *`CondorcetPHP\Condorcet\Election`*   
Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.    


#### **callBack:** *`?Closure`*   
Callback function to execute after each registered vote.    


### Return value:   

*(`CondorcetPHP\Condorcet\Election`)* The election object


---------------------------------------

### Related method(s)      

* [Tools\DavidHillFormat::setDataToAnElection](/Docs/ApiReferences/Tools\DavidHillFormat%20Class/public%20Tools\DavidHillFormat--setDataToAnElection.md)    
* [Tools\DebianFormat::setDataToAnElection](/Docs/ApiReferences/Tools\DebianFormat%20Class/public%20Tools\DebianFormat--setDataToAnElection.md)    
