## public Tools\Randomizers\VotesRandomGenerator::__construct

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Randomizers/VotesRandomGenerator.php#L39)

### Description    

```php
public Tools\Randomizers\VotesRandomGenerator->__construct ( array $candidates [, Random\Randomizer|string|null $seed = null] )
```

Create a new VotesRandomGenerator instance
    

#### **candidates:** *`array`*   
List of candidates as string, candidates objects or sub-array.    


#### **seed:** *`Random\Randomizer|string|null`*   
If null, will use a cryptographivcally secure randomier.    
