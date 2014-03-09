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
  
  
### Roadmap for further releases 
  
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

_(incoming)_

