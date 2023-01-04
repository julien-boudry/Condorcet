## public Election::getChecksum

### Description    

```php
public Election->getChecksum ( ): string
```

SHA-2 256 checksum of following internal data:
* Candidates
* Votes list & tags
* Computed data (pairwise, algorithm cache, stats)
* Class version (major version like 0.14)

Can be powerfull to check integrity and security of an election. Or working with serialized object.
    

### Return value:   

*(```string```)* SHA-2 256 bits Hexadecimal


---------------------------------------

### Tutorial

* **[This method has explanations and examples in the Documentation Book](https://www.condorcet.io#/3.AsPhpLibrary/7.GoFurther/CryptographicChecksum)**    
