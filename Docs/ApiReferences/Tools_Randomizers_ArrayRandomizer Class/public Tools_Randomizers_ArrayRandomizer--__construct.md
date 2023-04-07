## public Tools\Randomizers\ArrayRandomizer::__construct

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Tools/Randomizers/ArrayRandomizer.php#L36)

### Description    

```php
public Tools\Randomizers\ArrayRandomizer->__construct ( array $candidates [, Random\Randomizer|string|null $seed = null] )
```

Create a new VotesRandomGenerator instance
    

#### **candidates:** *`array`*   
List of candidates as string, candidates objects or sub-array.    


#### **seed:** *`Random\Randomizer|string|null`*   
If null, will use a cryptographically secure randomizer.    
