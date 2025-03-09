## public Election::getChecksum

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Election.php#L226)

### Description    

```php
public Election->getChecksum ( ): string
```

SHA-2 256 checksum of following internal data:
* Candidates
* Votes list & tags
* Computed data (pairwise, algorithm cache, stats)
* Class version (major version like 3.4)

Can be powerfull to check integrity and security of an election. Or working with serialized object.
    

### Return value:   

*(`string`)* SHA-2 256 bits Hexadecimal


---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io/3.AsPhpLibrary/7.GoFurther/CryptographicChecksum)**    
