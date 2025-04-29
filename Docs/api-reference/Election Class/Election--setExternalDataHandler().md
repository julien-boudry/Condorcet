# public Election::setExternalDataHandler

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L438)

## Description    

```php
public Election->setExternalDataHandler ( CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $driver ): static
```

Import and enable an external driver to store vote on very large election.

## Parameter

### **driver:** *`CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface`*   
Driver object.    


## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\DataHandlerException``` 

---------------------------------------

## Related

* [Election::removeExternalDataHandler()](/Docs/api-reference/Election%20Class/Election--removeExternalDataHandler().md)    

---------------------------------------

## Tutorial

* **[This method has explanations and examples in the Documentation Book](https://docs.condorcet.io/book/3.AsPhpLibrary/8.GoFurther/5.GetStartedToHandleMillionsOfVotes)**    
