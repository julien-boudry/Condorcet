# public protected(set) readonly Result::originalRankingAsArrayString

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Result.php#L24)

## Description    

```php
public protected(set) readonly array Result->originalRankingAsArrayString 
```

Get immutable result as an array
Unlike other methods to recover the result. This is frozen as soon as the original creation of the Result object is created.
Candidate objects are therefore protected from any change of candidateName, since the candidate objects are converted into a string when the results are promulgated.
This control method can therefore be useful if you undertake suspicious operations on candidate objects after the results have been promulgated.
