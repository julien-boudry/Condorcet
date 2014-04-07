Condorcet Class for PHP
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  
_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_

**Create by :** Julien Boudry (born 22/10/1988 - France)  
**License :** MIT _(read de LICENSE file at the root folder)_  
As a courtesy, I will thank you to inform me about your project wearing this code, produced with love and selflessness). You can also give me a bottle of good wine.

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

Enter (or not) an Option_Identifi
