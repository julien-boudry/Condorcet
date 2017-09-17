## public Election::getChecksum

### Description    

```php
public $Election -> getChecksum ( )
```

SHA-2 256 checksum of following internal data:
* Candidates
* Votes list & tags
* Computed data (pairwise, algorithm cache, stats)
* Class version (major version like 0.14)

Can be powerfull to check integrity and security of an election. Or working with serialized object.    


### Return value:   

(string) SHA-2 256 bits Hexadecimal


---------------------------------------

### Examples and explanation

* **[Manual - Cryptographic Checksum': 'https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features-%26-Configuration-%23-2.-Cryptographic-Checksum](https://github.com/julien-boudry/Condorcet/wiki/III-%23-A.-Avanced-features-%26-Configuration-%23-2.-Cryptographic-Checksum)**    
