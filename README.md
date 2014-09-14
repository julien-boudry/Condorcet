<p align="center">
  <img src="condorcet-logo.png" alt="Condorcet Class" width="40%">
</p>   

Condorcet Class for PHP
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  
_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_   

**Create by:** Julien Boudry (born 22/10/1988 - France) [@JulienBoudry](https://twitter.com/JulienBoudry) - _([complete list of contributors](https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class/graphs/contributors))_     
**License:** MIT _(read de LICENSE file at the root folder)_  Including code, examples, logo and documentation     
As a courtesy, I will thank you to inform me about your project wearing this code, produced with love and selflessness. **You can also offer me a bottle of good wine**.   
**Or finance my studies:** *1FavAXcAU5rNkfDDTgMs4xx1FNzDztwYV6*


### Project State
To date, we have a stable version. Since version 0.9, an important work of code review and testing was conducted by the creator, but **external testers are more than welcome**.   

- To date, the library is used by [Gustav Mahler blind listening test](http://classik.forumactif.com/t7244-ecoute-comparee-mahler-2e-symphonie-la-suite).   
http://gilles78.artisanat-furieux.net/Condorcet/
- From August 2014: Condorcet-Vote.org opens with a beta version. It is based in large part on this project, and uses the library as a real election manager.   

#### Specifications and standards  
**Stable Version: 0.14**  
**PHP Requirement:** PHP 5.5.12 with Ctype, MB_String, Json common extensions. _(tested up to PHP 5.6)_

**Autoloading:** This project is consistent with the standard-PSR 0 and can be loaded easily and without modification in most frameworks. Namespace \Condorcet is used. 
The literature also provides easy example of free implementation with or without autoloader.

**Coding standards:** The code is very close to the respect of PSR-1 (lacks only the naming of methods), and freely influenced by PSR-2 when it is not unnecessarily authoritarian.  


#### Related projects
* [Condorcet-Vote.org](http://www.condorcet-vote.org) Web services to create and store online Condorcet election. Including intérractives and collaborative features.   
* [Condorcet API](https://github.com/julien-boudry/Condorcet_API) Very basic and simple http API for Condorcet class (json or text i/o)
* [Mahler-S2-BlindTest-Condorcet
](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet) (french interface) Web wrapper to compute and show result for classical music blind challenge with the Condorcet Class full potential (can also be used and adapted for any elections). Look like the examples provided here, but better.    

---------------------------------------

## Features 

### To date

  The Condorcet Class provides any fine features as you need to manage AND computate yours polls. The code is efficient but do not use heuristic technics.  
  You should be able to find as much functionality and flexibility than you might expect.
  
#### Supported Condorcet Methods

* **Condorcet Basic** Give you the natural winner or looser of Condorcet, if there is one.  
*(This method is the only core method, you cannot remove it)*

* **Copeland** http://en.wikipedia.org/wiki/Copeland%27s_method

* **KemenyYoung** http://en.wikipedia.org/wiki/Kemeny-Young_method   
*Kemeny-Young is currently limited to elections not exédant 6 candidates. For reasons of performance almost insuperable. Solutions for a populated cache precalculated data are under review to reach 7 or 8 candidates, and maybe even 9.*

* **Minimax Family** http://en.wikipedia.org/wiki/Minimax_Condorcet
    * **Minimax_Winning** *(Does not satisfy the Condorcet loser criterion)*  
    * **Minimax_Margin** *(Does not satisfy the Condorcet loser criterion)*
    * **Minimax_Opposition**:warning: *By nature, this alternative does not meet any criterion of Condorcet.*

* **RankedPairs *(Since v0.10, EXPERIMENTAL)*** https://en.wikipedia.org/wiki/Ranked_pairs  

* **Schulze Family** http://en.wikipedia.org/wiki/Schulze_method
    * **Schulze** Schulze Winning is recommended by Markus Schulze himself. ***This is the default choice.***
    * **Schulze_Margin**
    * **Schulze_Ratio**

**Exept Condorcet Basic, the bold name of the above methods must be observed when you make calls, case sensitive.**   


#### Add new one?	
This class is designed to be easily extensible with new algorithms. A modular schematic is already used for all algorithms provided, so you can easily help, do not forget to make a pull request!  
[*More explanations in the documentation below*](#newAlgo) 
  
  
### Roadmap for futher releases 
  
  - Better cache system to prevent any full computing of the Pairwise on new vote / remove vote
  - Improve & test Ranked pair implementation *(help needed!)*
  - Kemeny-Young up to 7 candidates
  - **Looking for testers!**   
 

---------------------------------------
## How to use it?
You will find comprehensive examples in the example folder, read them! The following documentation is trying to be as complete as possible. Do not hesitate to ask questions.
We encourage you to read the code, and help to improve inline documentation!

1. [Install it](#install-it)
2. [Configure it if needed](#configure-it-if-needed)
3. [1: Manage Candidates](#1-manage-candidates)
4. [2: Start voting](#2-start-voting)
5. [3: Get results & Stats](#3-get-results--stats)
6. [Others tools](#tools)
7. [Exceptions codes](#exceptions)
8. [Customize the code: Add new algorithm(s)](#customize-the-code-add-new-algorithms-)

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
$condorcet->setMethod('Schulze') ; // Argument: A supported method. Return the string name of new default method for this object (
or forced method after the class if fonctionnalitée enabled). Throw an exception on error.
```

#### Change the class default method if needed
```php
Condorcet::setClassMethod('Schulze') ; // Argument: A supported method  
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

### Registering

#### Regular

```php
addCandidate ( [mixed $name = automatic] ) 
```
**name:** Alphanumeric string or int. Your candidate name will be trim()    

**Return value:** The new candidate name (your or automatic one). Throw an exception on error (existing candidate...)    

Enter (or not) a Candidate Name 

```php
$condorcet->addCandidate('Wagner') ; // mb_strlen(Candidate Name) <= self::MAX_LENGTH_CANDIDATE_ID, Default: 30
$condorcet->addCandidate('Debussy') ;  
$condorcet->addCandidate() ; // Empty argument will return an automatic candidate name for you (From A to ZZZZZ)  
$condorcet->addCandidate(2) ; // A numeric argument  
```

#### Add multiple candidates from string or text file

##### Syntax
```
Candidate1
Candidate2 # You can add optionnal comments
Candidate3 ; Candidate4 # Or in the same line separated by ;, with or without space (will be trim)
Candidate5
``` 

##### Method
```php
$condorcet->parseCandidates('data/candidates.txt'); // Path to text file. Absolute or relative.
$condorcet->parseCandidates($my_big_string); // Just my big string.
```

#### Add multiple candidates from Json

##### Syntax
```php
json_encode( array(
	'Candidate1',
	'Candidate2'
) );
``` 

##### Method
```php
$condorcet->jsonCandidates($my_big_string);
```

### Removing
```php
removeCandidate ( mixed $name = automatic )
```
**name:** Alphanumeric string or int.   

**Return value:** True on success. Throw an exception if candidate name can't be found or if the vote has began.


```php
$condorcet->removeCandidate('Wagner') ;
```


### Verify the Candidates list
```php
$condorcet->getCandidatesList(); // Will return an array with Candidate Name as value.
```

_Note: When you start voting, you will never be able to edit the candidates list._  


---------------------------------------
### 2: Start voting
_Note: All votes are adjusted to estimate all candidates. The pairwise is calculated accordingly._

#### Restrict the number of possible votes for one election
_Note: By default, there is no limit_  

```php
Condorcet::setMaxVoteNumber(2042); // All object, new or wake up, will be limit at this number.
Condorcet::setMaxVoteNumber(null); // No limit for evrybody. (Default)

$condorcet->ignoreMaxVote(true); // Ho well, I'm a rebel. This Class limit do not apply to me.
$condorcet->ignoreMaxVote(false); // Ok, ok, it apply to me.
```

#### Add a vote
_Note: You can add new votes after the results have already been given_  


```php
// addVote ( mixed $data [, mixed $tag = null] )
```
**data:** The vote data  
**tag:** add tag(s) to this vote for further actions



##### With an array
```php
$vote[1] = 'A' ;  
$vote[2] = 'Debussy' ;  
$vote[3] = 'Wagner' ;  
$vote[4] = 2 ; // The last rank is optionnal 
$condorcet->addVote($vote) ;  
```

Use commas in the case of a tie:  
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


##### Add a tag
You can add the same or different tag for each vote:  
```php
$condorcet->addVote($vote, 'Charlie') ; // Please note that a single tag is always created for each vote. 
$condorcet->addVote($vote, 'Charlie,Claude') ; // You can also add multiple tags, separated by commas. 
```   


#### Add multiple votes from string or text file
Once your list of candidates previously recorded. You can parse a text file or as a PHP string character to record a large number of votes at once.   

*You can simultaneously combine this method with traditional PHP calls above.*  

##### Syntax
```
tag1,tag2,tag3[...] || A>B=D>C # A comment at the end of the line prefixed by '#'. Never use ';' in comment!
Titan,CoteBoeuf || A>B=D>C # Tags at first, vote at second, separated by '||'
A>C>D>B # Line break to start a new vote. Tags are optionals. View above for vote syntax.
tag1,tag2,tag3 || A>B=D>C * 5 # This vote and his tag will be register 5 times
   tag1  ,  tag2, tag3     ||    A>B=D>C*2        # Working too.
C>D>B*8;A=D>B;Julien,Christelle||A>C>D>B*4;D=B=C>A # Alternatively, you can replace the line break by a semicolon.
``` 

##### Method
```php
$condorcet->parseVotes('data/vote42.txt'); // Path to text file. Absolute or relative.
$condorcet->parseVotes($my_big_string); // Just my big string.
```

#### Add multiple votes from Json

##### Syntax
```php
$json_votes = json_encode( array(
	array('vote' => 'A>B=D>C', 'tag' => 'ben,jerry'),
	array('vote' => array('D', 'B,A', 'C'), 'tag' => array('bela','bartok'), 'multi' => 5),
	array('vote' => array('A', array('B','C'), 'D'))
) );
``` 

In the previous example, all parameters are optional exept vote.
* 'multi' is used to record N times the vote.   
* 'tag' is used in the same way as addVote ()  
* 'vote' is used in the same way as addVote ()   

##### Method
```php
$condorcet->jsonVotes($json_votes);
```

**Anti-flood:**

Be applied and reset each call parseVotes() or jsonVotes()   

```php
Condorcet::setMaxParseIteration(500); // Will generate an exception and stop after 500 registered vote by call.
Condorcet::setMaxParseIteration(null); // No limit (default mode)
```  

#### Verify the registered votes list
```php
getVotesList ( [mixed $tag = null, bool $with = true] )
```
**tag:** List of tags   
**with:** With or without one a this tag(s)   

```php
$condorcet->getVotesList (); // Will return an array where key is the internal numeric vote_id and value an other array like your input.   
$condorcet->getVotesList ('Charlie'); // Will return an array with each vote with this tag.   
$condorcet->getVotesList ('Charlie', false); // Will return an array where each vote without this tag.   
$condorcet->getVotesList ('Charlie,Julien'); // With this tag OR this tag   
$condorcet->getVotesList (array('Julien', 'Charlie'), true); // Or do it like this   
$condorcet->getVotesList (array('Julien', 'Charlie'), false); // Without this tag AND without this tag ...   
```

__Note: Make a test, and look the return format. For each vote, you can get as a tag an unique ID and registered timestamp.__


#### Count registered votes

```php
countVotes ( [mixed $tag = null, bool $with = true] )
```
**tag:** List of tags   
**with:** With or without one a this tag(s)    

```php
$condorcet->countVotes (); // Return a numeric value about the number of registered votes.  
$condorcet->countVotes ('Julien,Charlie'); // Count vote with this tag OR this tag.   
$condorcet->countVotes (array('Julien','Charlie'), false); // Count vote without this tag AND without this tag.   
```


#### Remove vote
```php
removeVote( mixed $tag [, bool $with = true] )
```
**tag:** List of tags   
**with:** With or without one a this tag(s)    

```php
$condorcet->removeVote('Charlie') ; // Remove vote(s) with tag Charlie
$condorcet->removeVote('Charlie', false) ; // Remove votes without tag Charlie
$condorcet->removeVote('Charlie, Julien', false) ; // Remove votes without tag Charlie AND without tag Julien.
$condorcet->removeVote(array('Julien','Charlie')) ; // Remove votes with tag Charlie OR with tag Julien.
```

_Note: You can remove a vote after the results have already been given._  


---------------------------------------
### 3: Get results & Stats
When you have finished to process vote, you would like to have the results.

#### Just get the natural Condorcet Winner
```php
getWinner ( [mixed $method = false] )
getLoser ( [mixed $method = false] )
```
**method:** String name of an available advanced Condorcet method. True for default method.

**Return value:** Candidate name, null if there are no aviable winner or loser. Throw an exception on error.

##### Regular
```php
$condorcet->getWinner() ; // Will return a string with the Condorcet Winner candidate name
$condorcet->getLoser() ; // Will return a string with the Condorcet looser candidate name
```


##### Special
If there is not a regular Condorcet Winner or Loser, process to a special winner(s) using an advanced method.  

```php
$condorcet->getWinner(true) ; // With the default object method (Class Default: Schulze)  
$condorcet->getWinner('Schulze') ; // Name of an valid method  

$condorcet->getLoser(true) // With the default object method (Class Default: Schulze)  
$condorcet->getLoser('Schulze') ; // Name of an valid method  
```

Will return a string with the Candidate Name or many separated by commas  


#### Get a complete ranking from advanced methods
```php
getResult ( [mixed $method = false , array $extra_param = null, mixed $tag , bool $with = true] )
```
**method:** String name of an available advanced Condorcet method. True for default method.
**extra_param:** Specific for each method, if needed.
**tag:** Use a special set of votes.  
**with:** With or without one a this tag(s)   

**Return value:** Throw an exception on error, else a nice array for ranking.

__Warning: Using getResult() with tags filter don't use cache engin and computing each time you call it, prefer clone object and remove votes if you need it.__


```php
$condorcet->getResult() ; // Set of results with ranking from the default method. (Class Default: Schulze)  
$condorcet->getResult('Schulze') ; // Get the result for a valid method.
$condorcet->getResult( 'KemenyYoung', array('noConflict' => true) ) ; // Sometimes (actually only this one for KemenyYoung), you can use an array for some algorithm configuration. See details above.
$condorcet->getResult(true, null, 'Julien,Beethoven') ; // Use the default ranking method, no special parameters to it, but only compute with vote get tag 'Julien' or tag 'Beethoven'.
$condorcet->getResult('Copeland', null, 'Julien,Beethoven', false) ; // Use the Copeland methodd, no special parameters to it, but only compute with vote without tag 'Julien' and without tag 'Beethoven'.
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

#### Timer benchmarking 
These two methods can be useful in estimating the computation time of each call to the algorithm. It is not a calculation of all operations carried out by the library, but those specifically related to modules of algorithms for calculating election results.

#### Last timer
```php
getLastTimer (bool $float = false)
```
**float:** If true, return a float number. Else, it return number_format($result, 5).

**Return value:** Returns the CPU time measured during the last arithmetic operation results with getResult() getWinner() getLoser() OR the Pairwise with getPairwise().    


```php
$calculator->getPairwise();
$calculator->getLastTimer(); // Return the pairwise computation time ONLY if call before getResult(), getWinner(), getLoser(). Besause, cache system skip operation next time exept if there are new votes.

$calculator->getResult('RankedPairs');
$calculator->getLastTimer(); // Return 0.00112 (string)
$calculator->getResult('RankedPairs');
$calculator->getLastTimer(); // Return 0.00003 . See the cache system working!
$calculator->getResult('KemenyYoung');
$calculator->getLastTimer(true); // Return 0.14926002 (float) . KemenyYoung can be really slow....
$calculator->getResult('Copeland');
$calculator->getLastTimer(true); // Return 0.00010030 (float) . But Copeland is really fast!
```

#### Global timer
```php
getGlobalTimer (bool $float = false)
```
**float:** If true, return a float number. Else, it return number_format($result, 5).

**Return value:** Returns the CPU time measured in the overall operations of calculation results with getResult () getWinner () getLoser () AND the Pairwise. Since the creation of the object.    


```php
$calculator->getResult('RankedPairs');
$calculator->getResult('KemenyYoung');
$calculator->getResult('Copeland');
$calculator->getLastTimer(true); // Return 0.02600050 (float) . Time calculation, including that of the Pairwise
```


---------------------------------------
### Others tools  <a name="tools"></a>  

#### Cryptographic checksum
```php
getChecksum ()
```
**Return value:** SHA-2 256 checksum of folliwing internal data:
* Candidates
* Votes list & tags
* Computed data (pairwise, algorithm cache, stats)
* Class version (major version like 0.14)

Powerfull method to check the integrity after a wakeup action or detect some changes (new votes, new computing data...). Or also the need to rebuild object from scratch after a major update of Condorcet Class (working also with exception code 11 on wake up).    


---------------------------------------
### Exceptions code  <a name="exceptions"></a>  
	[1] = 'Bad candidate format';
	[2] = 'The voting process has already started';
	[3] = 'This candidate ID is already registered';
	[4] = 'This candidate ID do not exist';
	[5] = 'Bad vote format';
	[6] = 'You need to specify votes before results';
	[7] = 'Your Candidate ID is too long > ' . namespace\Condorcet::MAX_LENGTH_CANDIDATE_ID;
	[8] = 'This method do not exist';
	[9] = 'The algo class you want has not been defined';
	[10] = 'The algo class you want is not correct';
	[11] = 'You try to unserialize an object version older than your actual Class version. This is a problematic thing';
	[12] = 'You have exceeded the number of votes allowed for this method.';
	[13] = 'Formatting error: You do not multiply by a number!';
	[14] = 'parseVote() must take a string (raw or path) as argument';
	[15] = 'Input must be valid Json format';

	[101] = 'KemenyYoung is configured to accept only 6 candidates';


---------------------------------------

__There are many public method and public nstatic method. All of theme are not in this documentation, and of all them are not fully documented here ; read the code or ask here!__

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
