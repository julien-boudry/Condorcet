# public Candidate::setName

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Candidate.php#L123)

## Description    

```php
public Candidate->setName ( string $name ): static
```

Change the candidate name.
*If this will not cause conflicts if the candidate is already participating in elections and would namesake. This situation will throw an exception.*.

## Parameter

### **name:** *`string`*   
Candidate Name.    


## Throws:   

* ```CondorcetPHP\Condorcet\Throwable\CandidateInvalidNameException``` If the name exceeds the maximum allowed length, contains invalid characters, or is already taken by another candidate in the election context.
- Exceeds maximum length: The name length exceeds `Election::MAX_CANDIDATE_NAME_LENGTH`.
- Contains invalid characters: The name contains prohibited characters such as `<`, `>`, `\n`, `\t`, `\0`, `^`, `*`, `$`, `:`, `;`, `||`, `"`, or `#`.
- Name conflict: The name is already taken by another candidate in the election context.
