## public Tools\Converters\DebianFormat::setDataToAnElection

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Converters/DebianFormat.php#L43)

### Description    

```php
public Tools\Converters\DebianFormat->setDataToAnElection ( [?CondorcetPHP\Condorcet\Election $election = null] ): CondorcetPHP\Condorcet\Election
```

Add the Debian data to an election object
    

#### **election:** *`?CondorcetPHP\Condorcet\Election`*   
Add an existing election, useful if you want to set up some parameters or add extra candidates. If null an election object will be created for you.    


### Return value:   

*(`CondorcetPHP\Condorcet\Election`)* The election object


---------------------------------------

### Related method(s)      

* [Tools\CondorcetElectionFormat::setDataToAnElection](/Docs/ApiReferences/Tools\CondorcetElectionFormat%20Class/public%20Tools\CondorcetElectionFormat--setDataToAnElection.md)    
* [Tools\DavidHillFormat::setDataToAnElection](/Docs/ApiReferences/Tools\DavidHillFormat%20Class/public%20Tools\DavidHillFormat--setDataToAnElection.md)    
