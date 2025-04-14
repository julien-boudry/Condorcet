## public Tools\Converters\CEF\CondorcetElectionFormat::setDataToAnElection

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/CEF/CondorcetElectionFormat.php#L199)

### Description    

```php
public Tools\Converters\CEF\CondorcetElectionFormat->setDataToAnElection ( [CondorcetPHP\Condorcet\Election $election = new CondorcetPHP\Condorcet\Election , ?Closure $callBack = null] ): CondorcetPHP\Condorcet\Election
```

Add the data to an election object
    

#### **election:** *`CondorcetPHP\Condorcet\Election`*   
Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.    


#### **callBack:** *`?Closure`*   
Callback function to execute after each registered vote.    


### Return value   

*(`CondorcetPHP\Condorcet\Election`)* The election object


---------------------------------------

### Related method(s)      

* [Tools\Converters\DavidHillFormat::setDataToAnElection](/Docs/ApiReferences/Tools_Converters_DavidHillFormat%20Class/public%20Tools_Converters_DavidHillFormat--setDataToAnElection.md)    
* [Tools\Converters\DebianFormat::setDataToAnElection](/Docs/ApiReferences/Tools_Converters_DebianFormat%20Class/public%20Tools_Converters_DebianFormat--setDataToAnElection.md)    
