Condorcet Class for PHP
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  
_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_   

**Create by :** Julien Boudry (born 22/10/1988 - France) [@JulienBoudry](https://twitter.com/JulienBoudry)     
**License :** MIT _(read de LICENSE file at the root folder)_  
As a courtesy, I will thank you to inform me about your project wearing this code, produced with love and selflessness. **You can also offer me a bottle of good wine**.   
**Or finance my studies:** *1FavAXcAU5rNkfDDTgMs4xx1FNzDztwYV6*


### Project State
To date, the 0.9 is a stable version. Since version 0.9, an important work of code review and testing was conducted by the creator, but **external testers are more than welcome**.   

- To date, the library is used by [Gustav Mahler blind listening test](http://classik.forumactif.com/t7244-ecoute-comparee-mahler-2e-symphonie-la-suite).   
http://gilles78.artisanat-furieux.net/Condorcet/

#### Specifications and standards  
**Stable Version : 0.9**  
**PHP Requirement:** PHP 5.4 with Ctype and MB_String common extensions  

**Autoloading:** This project is consistent with the standard-PSR 0 and can be loaded easily and without modification in most frameworks. Namespace \Condorcet is used. 
The literature also provides easy example of free implementation with or without autoloader.

**Coding standards:** The code is very close to the respect of PSR-1 (lacks only the naming of methods), and freely influenced by PSR-2 when it is not unnecessarily authoritarian.  


---------------------------------------

## Features 

### To date

  The Condorcet Class provides any fine features as you need to manage and computate yours polls. The code is efficient but do not use heuristic technics.  
  You should be able to find as much functionality and flexibility than you might expect.
  
#### Supported Condorcet Methods

* **Condorcet Basic** Give you the natural winner or looser of Condorcet, if there is one.  
*(This method is the only core method, you cannot remove it)*

* **Copeland** *(Since v0.4)* http://en.wikipedia.org/wiki/Copeland%27s_method

* **KemenyYoung** *(Since v0.8)* http://en.wikipedia.org/wiki/Kemeny-Young_method   
*Neither perfect nor quick, however this implementation is operating normally. You are invited to read the corresponding Github issue, and if you are able to comment on issues in Github.   
Because this implementation uses no heuristic means, it is deliberately restrained to vote involving no more than 5 candidates; however, you can easily bypass this limitation by using ```namespace\KemenyYoung::setMaxCandidates(6);```, but your processor could work a long time when exceeding six candidates. In contrast, the number of voters do not impact the calculation time significantly.*

* **Minimax Family** *(Since v0.6)* http://en.wikipedia.org/wiki/Minimax_Condorcet
    * **Minimax_Winning** *(Does not satisfy the Condorcet loser criterion)*  
    * **Minimax_Margin** *(Does not satisfy the Condorcet loser criterion)*
    * **Minimax_Opposition** :warning: *By nature, this alternative does not meet any criterion of Condorcet.*

* **Schulze** http://en.wikipedia.org/wiki/Schulze_method


_The name of the above methods must be observed when you make calls, case sensitive._

#### Add new one?	
This class is designed to be easily extensible with new algorithms. A modular schematic is already used for all algorithms provided, so you can easily help, do not forget to make a pull request!  
[*More explanations in the documentation below*](#newAlgo) 
  
  
### Roadmap for futher releases 
  
  - Better cache system to prevent any full computing of the Pairwise on new vote / remove vote
  - Perhaps Ranked pairs Condorcet methods *(help needed!)*
  - Non-Condorcet fun methods? (majority, points?)
  - **Looking for testers!**   
 

---------------------------------------
## How to use it?
Soon you will find a complete example.php. The most important methods are listed bellow.  
We encourage you to read the code, and help to improve inline documentation!

1. [Install it](#install-it)
2. [Configure it if needed](#configure-it-if-needed)
3. [1: Manage Candidates](#1-manage-candidates)
4. [2: Start voting](#2-start-voting)
5. [3: Get results & Stats](#3-get-results--stats)
6. [Customize the code : Add new algorithm(s)](#customize-the-code--add-new-algorithms-)

---------------------------------------
### Install it

#### Basically
```php
require_once 'Condorcet.php' ; // Customize the path for your use.
use Condorcet\Condorcet ; // Optionnal if you prefer to use the full namespace length

$condorcet = new Condorcet () ; // You can specify as an argument, the name string of method instead of default Schulze Method.  
```

#### Example with the official PSR-0 example of Autoloader
```php
// PSR-0 style Loader

	// ENTER HERE THE PATH TO YOUR LIB FOLDER
	define('LIB_PATH', 'lib'.DIRECTORY_SEPARATOR);

	function autoload($className)
	{
	    $className = ltrim($className, '\\');
	    $fileName  = '' ;
	    $namespace = '';
	    if ($lastNsPos = strripos($className, '\\')) {
	        $namespace = substr($className, 0, $lastNsPos);
	        $className = substr($className, $lastNsPos + 1);
	        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	    }
	    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	    require LIB_PATH.$fileName;
	}

	spl_autoload_register('autoload');


///// STARTING

use Condorcet\Condorcet ; // Optional!

$condorcet = new Condorcet (); // If you omit the previous line, do: new Condorcet\Condorcet () ;

```

#### With Frameworks
*Read the doc! The Condorcet folder inside the lib directory can be move into your solution lib directory*


#### With Composer
`composer require julien-boudry/condorcet`

Look https://packagist.org/packages/julien-boudry/condorcet  

---------------------------------------
### Configure it if needed

#### Change the object default method if needed
```php
$condorcet->setMethod('Schulze') ; // Argument : A supported method  
```

#### Change the class default method if needed
```php
Condorcet::setClassMethod('Schulze') ; // Argument : A supported method  
Condorcet::setClassMethod('Schulze', true) ; // Will force actual and futher object to use this by default.  
Condorcet::forceMethod(false) ; // Unforce actual and futher object to use the class default method (or force it if argument is true)
```

#### Get information 
```php
$condorcet->getConfig (); // Will return an explicit array about the object and Class Constant.  

$condorcet->getMethod (); // Return a string with the name of the default method in use for this object, including if the force class Constant is defined to true.  

Condorcet::getAuthMethods (); // Get an array of authorized methods to use with the correct string to use as parameter.  
```

#### Get library version / Get object version

The distinction may be useful in the case of a storage of the object in the database.
```php
Condorcet::getClassVersion();  // Return the Class engine
$condorcet->getObjectVersion(); // Return the Class engine who build this object
```

#### Reset object without destroying it (discouraged pratice)
```php
$condorcet->resetAll ();
``` 

---------------------------------------
### 1: Manage Candidates

#### Registering

Enter (or not) a Candidate Name 

```php
$condorcet->addCandidate('Wagner') ; // mb_strlen(Candidate Name) <= self::MAX_LENGTH_CANDIDATE_ID, Default : 30
$condorcet->addCandidate('Debussy') ;  
$condorcet->addCandidate() ; // Empty argument will return an automatic candidate name for you (From A to ZZZZZ)  
$condorcet->addCandidate(2) ; // A numeric argument  
```


#### Removing
```php
$condorcet->removeCandidate('Wagner') ;
```


#### Verify the Candidates list
```php
$condorcet->getCandidatesList(); // Will return an array with Candidate Name as value.
```

_Note: When you start voting, you will never be able to edit the candidates list._  


---------------------------------------
### 2: Start voting
_Note: All votes are adjusted to estimate all candidates. The pairwise is calculated accordingly._

#### Add a vote
_Note: You can add new votes after the results have already been given_  

##### With an array
```php
$vote[1] = 'A' ;  
$vote[2] = 'Debussy' ;  
$vote[3] = 'Wagner' ;  
$vote[4] = 2 ; // The last rank is optionnal 
$condorcet->addVote($vote) ;  
```

Use commas in the case of a tie :  
```php
$vote[1] = 'A,Wagner' ;  
$vote[2] = 'Debussy' ;  
$condorcet->addVote($vote) ; 
```

*The last rank is optionnal, it will be automatically deducted.*  

##### With a string
You can do like this:

```php
$vote = 'A>B=C=H>G=T>Q' ;
$condorcet->addVote($vote) ;  

// It's working with some space, if you want to be durty...
$vote = 'A> B = C=H >G=T > Q' ;
$condorcet->addVote($vote) ;  

// But you can not use '<' operator
$vote = 'A<B<C' ; // It's not correct
// Follow can't work too
$vote = 'A>BC>D' ; // It's not correct
```

*The last rank is optionnal too, it will be automatically deducted.*  


#### Add a tag
You can add the same or different tag for each vote:  
```php
$condorcet->addVote($vote, 'Charlie') ; // Please note that a single tag is always created for each vote. 
$condorcet->addVote($vote, 'Charlie,Claude') ; // You can also add multiple tags, separated by commas. 
```



#### Verify the registered votes list
```php
$condorcet->getVotesList (); // Will return an array where key is the internal numeric vote_id and value an other array like your input.   
$condorcet->getVotesList ('Charlie'); // Will return an array where each vote with this tag.   
$condorcet->getVotesList ('Charlie', false); // Will return an array where each vote without this tag.   

$condorcet->countVotes (); // Return a numeric value about the number of registered votes.  
```


#### Remove vote
```php
$condorcet->removeVote('Charlie') ; // Remove vote(s) with tag Charlie
$condorcet->removeVote('Charlie', false) ; // Remove votes without tag Charlie
```

_Note: You can remove a vote after the results have already been given._  


---------------------------------------
### 3: Get results & Stats
When you have finished to process vote, you would like to have the results.

#### Just get the natural Condorcet Winner

##### Regular
```php
$condorcet->getWinner() ; // Will return a string with the Candidate name
$condorcet->getLoser() ; // Will return a string with the Candidate name
```


##### Special
If there is not a regular Condorcet Winner or Loser, process to a special winner(s) using an advanced method.  

```php
$condorcet->getWinner(true) ; // With the default object method (Class Default : Schulze)  
$condorcet->getWinner('Schulze') ; // Name of an valid method  

$condorcet->getLoser(true) // With the default object method (Class Default : Schulze)  
$condorcet->getLoser('Schulze') ; // Name of an valid method  
```

Will return a string with the Candidate Name or many separated by commas  


#### Get a complete ranking from advanced methods
```php
$condorcet->getResult() ; // Set of results with ranking from the default method. (Class Default: Schulze)  
$condorcet->getResult('Schulze') ; // Get the result for a valid method.
$condorcet->getResult( 'KemenyYoung', array('noConflict' => true) ) ; // Sometimes (actually only this one for KemenyYoung), you can use an array for some algorithm configuration. See details above.
```


#### Get compute details
```php
$condorcet->getPairwise() ; // Return an explicit array using your Candidate Name as keys.  

$condorcet->getResultStats() ; // Get stats about computing result for the default object method. (Class Default: Schulze)  
$condorcet->getResultStats('Schulze') ; // Same thing with a specific method.  
```

#### Specials options on getResult()

##### Kemeny-Young
Currently Kemeny-Young is potentially subject to conflict leading to a relatively arbitrary final choice. Very likely thing in the case of a very small number of voters. The current implementation does not include any trick to the resolver.   

The next option allows you to get rather than ranking, information on the existence or the absence of these conflicts. The following example mounts how to you use it.   

```php
$test = $condorcet->getResult( 'KemenyYoung', array('noConflict' => true) ) ;

if ( is_string($test) ) // There is conflicts
{
	$test = explode(";",$test);

	echo 'Arbitrary results: Kemeny-Young has '.$test[0].' possible solutions at score '.$test[1] ;
}
else
{
	// $test is your habitual result ;
}
```    

---------------------------------------
### Customize the code: Add new algorithm(s) <a name="newAlgo"></a>  
Look at how existing algorithms work in the "algorithms" folder, because the algorithms formally included using the same modular schema. *(Exept Condorcet_Basic, which is the only core algorithm and a little bit special.)*

**Each new class of algorithm must implements the Condorcet_Algo interface:** 

##### Constructor take an array as follow: 

```php
$param['_CandidatesCount'] // Number of candidate
$param['_Candidates'] // Candidate list
$param['_Votes'] // In case you would need to do more complicated things than just work on Pairwise...
$param['_Pairwise'] // The Pairwise
```

##### Link your algorithm

The class name should be in this format: 
```php
class AlgorithmName implements namespace\Condorcet_Algo  
```
File on disk must follow this format: `AlgorithmName.algo.php`  

You must register this algorithm this way:  
```php
Condorcet::addAlgos('AlgorithmName') ;
```  

You can specify it as default algorithm:  

_[See the appropriate instructions at the top](#change-the-class-default-method-if-needed)_  
