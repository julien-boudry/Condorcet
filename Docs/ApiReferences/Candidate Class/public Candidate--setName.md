## public Candidate::setName

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Candidate.php#L63)

### Description    

```php
public Candidate->setName ( string $name ): true
```

Change the candidate name.
*If this will not cause conflicts if the candidate is already participating in elections and would namesake. This situation will throw an exception.*
    

#### **name:** *`string`*   
Candidate Name.    


### Return value:   

*(`true`)* In case of success, return TRUE



### Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException```
