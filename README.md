Condorcet Class for PHP with Schulze method and others (_scheduled_)
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  

_To date, only the Condorcet winner and full Schulze result with ranks are available. The class is designed to be extended to other methods in the future or by your own PHP inheritance._  
**Condorcet :** http://en.wikipedia.org/wiki/Condorcet_method  
**Schulze :**   http://en.wikipedia.org/wiki/Schulze_method

_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_

### Project State

**This open software project is beginning and needs your help for testing, improved documentation and features**  

- To date, the 0.1 version is not ready for production due to lack of testing.

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
`Condorcet::setClassMethod('Schulze', true) ;` Will force actual and futher object to use this by default 

`Condorcet::forceMethod(false) ;` Unforce actual and futher object to use the class default method by default _(force if argument is true)_  


#### Vote options

##### Registering

Enter or not an Option identifiant  

`$condorcet->add_option('Wagner') ;`  mb_strlen(Alphanum option) <= self::LENGTH_OPION_ID _(10)_
`$condorcet->add_option('Debussy') ;`  
`$condorcet->add_option() ;` Empty argument will return an automatic for you _(From A to ZZZZZ)_  
`$condorcet->add_option(2) ;` A numeric argument  


##### Removing

`$condorcet->remove_option('Wagner') ;`  



_Note : When you start voting, you will never can edit the options list._  


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


_Note : You can add vote after asking the results._  


#### Get result

When you finished to processing vote, you may wants the results.

##### Just get the Condorcet Winner

###### Regular

`$condorcet->get_winner_Condorcet() ;` Will return a string with the Option identifiant  


###### Special

Only if there is not a regular Condorcet Winner, process to a special winner use an advanced winner.  

`$condorcet->get_winner_Condorcet(true) ;` With the default object method _(Class Default : Schulze)_  
`$condorcet->get_winner_Condorcet('Schulze') ;` Name of an valid method  

Will return a string with the Option identifiant or many Option identifiants separated by commas  


##### Get a complete ranking from advanced methods

`$condorcet->get_result() ;` Set of results with ranking from the default method _(Class Default : Schulze)_  
`$condorcet->get_result('Schulze') ;` Change the default object method and get a set of results with ranking from the default method  


###### If you want a specific result without changing the object default method :  

`$condorcet->get_result_Schulze () ;` Example for Schulze Method  


##### Get compute details

_Note : Working, but not really helpfull with 0.1 version. V0.2 will provide better return_  

`$condorcet->get_Pairwise() ;` Return an array  
`$condorcet->get_Strongest_Paths() ;` ; Return an array  


