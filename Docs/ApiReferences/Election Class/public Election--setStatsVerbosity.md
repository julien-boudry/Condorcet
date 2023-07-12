## public Election::setStatsVerbosity

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L247)

### Description    

```php
public Election->setStatsVerbosity ( CondorcetPHP\Condorcet\Algo\StatsVerbosity $StatsVerbosity ): void
```

Set a verbosity level for Result->statsVerbosity on returning Result objects. High level can slow down processing and use more memory (many more) than LOW and STD (default) level on somes methods.
    

#### **StatsVerbosity:** *`CondorcetPHP\Condorcet\Algo\StatsVerbosity`*   
A verbosity level.    

---------------------------------------

### Related method(s)      

* [Election::getVerbosity](/Docs/ApiReferences/Election%20Class/public%20Election--getVerbosity.md)    
* [Result::getVerbosity](/Docs/ApiReferences/Result%20Class/public%20Result--getVerbosity.md)    
