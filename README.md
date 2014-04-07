Condorcet Class for PHP
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  
_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_   

**Create by :** Julien Boudry (born 22/10/1988 - France)  
**License :** MIT _(read de LICENSE file at the root folder)_  
As a courtesy, I will thank you to inform me about your project wearing this code, produced with love and selflessness). You can also offer me a bottle of good wine.  


### Project State

**This open software project is beginning and needs your help for testing, improved documentation and features**  

**Stable Version : 0.4**  
**PHP Requirement :** PHP 5.4 with Ctype and MB_String common extensions

- To date, the 0.4 version is the first stable version, but still suffers from a lack of testing, especially on an advanced use of functional pannel. 


* * *

## Features 

### To date

  The Condorcet Class provides any fine features as you need to manage and computate yours polls. The code is efficient but do not use heuristic technics.  
  You should be able to find as much functionality and flexibility than you might expect.
  
#### Supported Condorcet Methods
	
##### Provides :

* **Condorcet Basic** Give you the natural winner or looser of Condorcet, if there is one.  
*(This method is the only core method, you can't remove it)*
* **Schulze** http://en.wikipedia.org/wiki/Schulze_method
* **Copeland** *(Since 0.4)* http://en.wikipedia.org/wiki/Copeland%27s_method

##### Add new  ?
	
This class is designed to be easily extensible with new algorithms. A modular schematic is used to totu algorithms provided, so you can easily help, do not forget to make a pull request!  
*More explanations in the documentation below.*
  
  
### Roadmap for futher releases 
  
  - Better cache system to prevent any full computing of the Pairwise on new vote / remove vote
  - Official support for exporting object with caching
  - New Condorcet methods
  - New ways to register votes & options
  - **Looking for testers !**
  
  

## How to use it ?


Soon you will find a complete example.php. The most important methods are listed bellow.  
We encourage you to read the code, and help to improve inline documentation !

_**(just some basic examples, incoming more)**_

#### Create new object & Class configuration

```php
require_once 'Condorcet.class.php' ;  
$condorcet = new Condorcet ($method = null) ; // You can specify a method instead of default Schulze Method.  
```

##### Change the object default method if needed

```php
$condorcet->setMethod('Schulze') ; // Argument : A supported method  
```


##### Change the class default method if needed

```php
Condorcet::setClassMethod('Schulze') ; // Argument : A supported method  
Condorcet::setClassMethod('Schulze', true) ; // Will force actual and futher object to use this by default.  
Condorcet::forceMethod(false) ; // Unforce actual and futher object to use the class default method  _(or force it if argument is true)_  
```


##### About errors

```php
Condorcet::setError(false) ; // _(true by default)_ Unactive or active trigger_error() usage. Can be unefficent depending of your configuration environnement.    
```



##### Get informations 

```php
$condorcet->get_config (); // Will return an explicit array about the object and Class Constant.  

$condorcet->get_method (); // Return a string with the name of the default method in use for this object, including if the force class Constant is defined to true.  

Condorcet::get_auth_methods (); // Get an array of authorized methods to use with the correct string to use as parameter.  
```

##### Reset object without destroy it _(discouraged pratice)_

```php
$condorcet->reset_all ();
``` 


#### Vote options

##### Registering

Enter (or not) an Option_Identifiant  

```php
$condorcet->add_option('Wagner') ; // mb_strlen(Alphanum option) <= self::LENGTH_OPION_ID _Default : 10_
$condorcet->add_option('Debussy') ;  
$condorcet->add_option() ; // Empty argument will return an automatic Option_ID for you _(From A to ZZZZZ)_  
$condorcet->add_option(2) ; // A numeric argument  
```


##### Removing

```php
$condorcet->remove_option('Wagner') ;
```


##### Verify the Options list

```php
$condorcet->get_options_list (); // Will return an array with Option_ID as value.
```

_Note : When you start voting, you will never be able to edit the options list._  


#### Start voting

For each vote, an orderer list from 1

```php
$vote[1] = 'A' ;  
$vote[2] = 'Debussy' ;  
$vote[3] = 'Wagner' ;  
$vote[4] = 2 ; // _The last rank is optionnal_  
$condorcet->add_vote($vote) ;  
```

Use commas in the case of a tie :  
```php
$vote[1] = 'A,Wagner' ;  
$vote[2] = 'Debussy' ;  
$condorcet->add_vote($vote) ; 
```

You can add the same or different tag for each vote :  
```php
$condorcet->add_vote($vote, 'Charlie') ; // Please note that a single tag is always created for each vote. 
$condorcet->add_vote($vote, 'Charlie,Claude') ; // You can also add multiple tags, separated by commas. 
```


_Note : You can add new vote after the results have already been given_  


##### Verify the registered votes list

```php
$condorcet->get_votes_list (); // Will return an array where key is the internal numeric vote_id and value an other array like your input.   
$condorcet->get_votes_list ('Charlie'); // Will return an array where each vote with this tag.   
$condorcet->get_votes_list ('Charlie', false); // Will return an array where each vote without this tag.   

$condorcet->count_votes (); // Return a numeric value about the number of registered votes.  
```


##### Remove vote

```php
$condorcet->remove_vote('Charlie') ; // Remove vote(s) with tag Charlie
$condorcet->remove_vote('Charlie', false) ; // Remove votes without tag Charlie
```

_Note : You can remove a vote after the results have already been given_  



#### Get result

When you have finished to processing vote, you would like to have the results.

##### Just get the natural Condorcet Winner

###### Regular

```php
$condorcet->get_winner() ; // Will return a string with the Option_Identifiant  
$condorcet->get_loser() ; // Will return a string with the Option_Identifiant  
```


###### Special

If there is not a regular Condorcet Winner or Loser, process to a special winner(s) using an advanced method.  

```php
$condorcet->get_winner(true) ; // With the default object method _(Class Default : Schulze)_  
$condorcet->get_winner('Schulze') ; // Name of an valid method  

$condorcet->get_loser(true) With the default object method _(Class Default : Schulze)_  
$condorcet->get_loser('Schulze') ; // Name of an valid method  
```

Will return a string with the Option_Identifiant or many Option identifiants separated by commas  


##### Get a complete ranking from advanced methods

```php
$condorcet->get_result() ; // Set of results with ranking from the default method. _(Class Default : Schulze)_  
$condorcet->get_result('Schulze') ; // Get a the result for a valid method.
```


##### Get compute details

```php
$condorcet->get_Pairwise() ; // Return an explicit array using your Option_ID as keys.  

$condorcet->get_result_stats() ; // Get stats about computing result for the default object method. _(Class Default : Schulze)_  
$condorcet->get_result_stats('Schulze') ; // Same thing with a specific method.  
```



##### Add new algorithm(s)  


Look at how existing algorithm work in the "algorithms" folder. *(Exept Condorcet Basic, which is the only core algorithm and a little bit special.)*

**Each new class of algorithm must include the publics methods:** 

1. get_result  
2. get_stats  
3. get_winner  
4. get_loser   

**Constructor take an array as follow:** 

```php
$param['_Pairwise'] = (Calculated Pairwise) ;
$param['_options_count'] = (Number of vote option) ;
$param['_options'] = (List of option) ;
$param['_votes'] = (Vote details) ; // In case you would need to do more complicated things than just work on Pairwise ...
```


The class name should be in this format: 
```php
class Condorcet_ALGORITHM-NAME  
```

You must register this algorithm via this way:  
```php
Condorcet::add_algos('ALGORITHM-NAME') ;
```  

You can specify it as default algorithm:  

_See the appropriate instructions above._  
