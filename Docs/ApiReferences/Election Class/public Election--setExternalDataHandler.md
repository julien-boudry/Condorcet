## public Election::setExternalDataHandler

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L462)

### Description    

```php
public Election->setExternalDataHandler ( CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $driver ): static
```

Import and enable an external driver to store vote on very large election.
    

#### **driver:** *`CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface`*   
Driver object.    


### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\DataHandlerException``` 

---------------------------------------

### Related method(s)      

* [Election::removeExternalDataHandler](/Docs/ApiReferences/Election%20Class/public%20Election--removeExternalDataHandler.md)    

---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/7.GoFurther/GetStarteToHandleMillionsOfVotes)**    
