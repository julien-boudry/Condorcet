## public Election::setExternalDataHandler

### Description    

```php
public Election->setExternalDataHandler ( CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface $driver ): bool
```

Import and enable an external driver to store vote on very large election.
    

##### **driver:** *```CondorcetPHP\Condorcet\DataManager\DataHandlerDrivers\DataHandlerDriverInterface```*   
Driver object.    


### Return value:   

*(```bool```)* True if success. Else throw an Exception.



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\DataHandlerException```

---------------------------------------

### Related method(s)      

* [Election::removeExternalDataHandler](../Election%20Class/public%20Election--removeExternalDataHandler.md)    

---------------------------------------

### Examples and explanation

* **[[Manual - DataHandler]](https://github.com/julien-boudry/Condorcet/blob/master/examples/specifics_examples/use_large_election_external_database_drivers.php)**    
