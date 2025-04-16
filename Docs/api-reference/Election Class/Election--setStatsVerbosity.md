## public Election::setStatsVerbosity

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/ElectionProcess/ResultsProcess.php#L280)

### Description    

```php
public Election->setStatsVerbosity ( CondorcetPHP\Condorcet\Algo\StatsVerbosity $StatsVerbosity ): static
```

Set a verbosity level for Result->statsVerbosity on returning Result objects. High level can slow down processing and use more memory (many more) than LOW and STD (default) level on somes methods.
    

#### **StatsVerbosity:** *`CondorcetPHP\Condorcet\Algo\StatsVerbosity`*   
A verbosity level.    

---------------------------------------

### Related method(s)      

* [Election::statsVerbosity](/Docs/api-reference/Election%20Class/Election--statsVerbosity.md)    
* [Result::statsVerbosity](/Docs/api-reference/Result%20Class/Result--statsVerbosity.md)    
