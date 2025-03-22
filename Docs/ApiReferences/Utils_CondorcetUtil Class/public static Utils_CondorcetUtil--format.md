## public static Utils\CondorcetUtil::format

> [Read it at the source](https://github.com/julien-boudry/Condorcet/blob/master/src/Utils/CondorcetUtil.php#L80)

### Description    

```php
public static Utils\CondorcetUtil::format ( mixed $input [, bool $convertObject = true] ): mixed
```

Provide pretty re-formatting, human compliant, of all Condorcet PHP object or result set.
Can be use before a var_dump, or just to get more simple data output.
    

#### **input:** *`mixed`*   
Input to convert.    


#### **convertObject:** *`bool`*   
If true. Will convert Candidate objects into string representation of their name.    


### Return value:   

*(`mixed`)* New formatted data.

