Condorcet Class for PHP with Schulze method and others (_scheduled_)
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  

_To date, only the Condorcet winner and full Schulze result with ranks are available. The class is designed to be extended to other methods in the future or by your own PHP inheritance._  
**Condorcet :** http://en.wikipedia.org/wiki/Condorcet_method  
**Schulze :**   http://en.wikipedia.org/wiki/Schulze_method

_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_

### Project State

**This open software project is beginning and needs your help for testing, improved documentation and features**  

**Stable Version : 0.2**
**PHP Requirement :** PHP 5.4 with Ctype and MB_String common extensions

- To date, the 0.2 version is not ready for production due to lack of testing.  


* * *

## Features 

### To date

  The Condorcet Class provides any fine features as you need to manage and computate yours polls. The code is efficient but do not use heuristic technics, and gets an archaic but efficient internal cache system.
  
  The Condorcet Winner and full Schulze with ranks are supported. Code is ready to be upgraded soon.
  
  
### Roadmap for futher releases 
  
  - Ability to remove vote
  - Better cache system to prevent any full computing of the Pairwise on new vote / remove vote
  - Official support for exporting object with caching
  - New Condorcet methods
  - Computing Condorcet looser
  - New analytic methods
  - New ways to register votes & options
  
  

## How to use it ?


Soon you will find a complete example.php. The most important methods are listed bellow.  
We encourage you to read the code, and help to improve inline documentation !

_**(just some basic examples, incoming more)**_

#### Create new object

` require_once 'Condorcet.class.php' ;`  
`$condorcet = new Condorcet ($method = null) ;` You can specify a method instead of default Schulze Method.  

##### Change the object default methode if needed

`$condorcet->setMethod('Schulze') ;` Argument : A supported method  


##### Change the class default methode if needed

`Condorcet::setClassMethod('Schulze') ;` Argument : A supported method  
`Condorcet::setClassMethod('Schulze', true) ;` Will force actual and futher object to use this by default.  
`Condorcet::forceMethod(false) ;` Unforce actual and futher object to use the class default method  _(or force it if argument is true)_  


##### Change the class default methode if needed

`Condorcet::setError(false) ;` _(true by default)_ Unactive or active trigger_error() usage. Can be unefficent depending of your configuration environnement.    



##### Get informations 

`$Condorcet->get_config ();` // Will return an explicit array about the object and Class Constant.  

`$Condorcet->get_method ();` // Return a string with the name of the default method in use for this object, including if the force class Constant is defined to true.  

`$Condorcet->get_auth_methods ();` Get an array of authorized methods to use.  


##### Reset object without destroy it _(discouraged)_

`$Condorcet->reset_all ();`  


#### Vote options

##### Registering

Enter (or not) an Option_Identifiant  

`$condorcet->add_option('Wagner') ;`  mb_strlen(Alphanum option) <= self::LENGTH_OPION_ID _(10)_
`$condorcet->add_option('Debussy') ;`  
`$condorcet->add_option() ;` Empty argument will return an automatic Option_ID for you _(From A to ZZZZZ)_  
`$condorcet->add_option(2) ;` A numeric argument  


##### Removing

`$condorcet->remove_option('Wagner') ;`  


##### Verify the Options list

`$Condorcet->get_options_list ();` // Will return an array with Option_ID as value.


_Note : When you start voting, you will never be able to edit the options list._  


#### Start voting

For each vote, an orderer list from 1

`$vote[1] = 'A' ;`  
`$vote[2] = 'Debussy' ;`  
`$vote[3] = 'Wagner' ;`  
`$vote[4] = 2 ;`  _The last rank is optionnal_  
`$condorcet->add_vote($vote) ;`  

Use commas in the case of a tie :  
`$vote[1] = 'A,Wagner' ;`  
`$vote[2] = 'Debussy' ;`  
`$condorcet->add_vote($vote) ;`  


##### Verify the registered votes list

`$Condorcet->get_votes_list ();` // Will return an array where key is the internal numeric vote_id and value an other array like your input.  

`$Condorcet->count_votes ();` // Return a numeric value about the number of registered votes.  



_Note : You can add new vote after the results have already been given_  


#### Get result

When you have finished to processing vote, you would like to have the results.

##### Just get the Condorcet Winner

###### Regular

`$condorcet->get_winner_Condorcet() ;` Will return a string with the Option_Identifiant  


###### Special

Only if there is not a regular Condorcet Winner, process to a special winner(s) using an advanced method.  

`$condorcet->get_winner_Condorcet(true) ;` With the default object method _(Class Default : Schulze)_  
`$condorcet->get_winner_Condorcet('Schulze') ;` Name of an valid method  

Will return a string with the Option_Identifiant or many Option identifiants separated by commas  


##### Get a complete ranking from advanced methods

`$condorcet->get_result() ;` Set of results with ranking from the default method _(Class Default : Schulze)_  
`$condorcet->get_result('Schulze') ;` Change the default object method and get a set of results with ranking from the default method  


###### If you want a specific result without changing the object default method :  

`$condorcet->get_result_Schulze () ;` Example for Schulze Method  


##### Get compute details

`$condorcet->get_Pairwise() ;` Return an explicit array using your Option_ID as keys.  
`$condorcet->get_Strongest_Paths() ;` ; Return an explicit array using your Option_ID as keys.


