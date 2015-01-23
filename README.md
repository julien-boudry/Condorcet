<p align="center">
  <img src="condorcet-logo.png" alt="Condorcet Class" width="40%">
</p>   

Condorcet Class for PHP
===========================

A PHP class implementing the Condorcet voting system and other Condorcet methods like the Schulze method.  
_This class is not designed for high performances, very high fiability exigence, massive voters or candidates_   

This library allows both the calculation of results according to the original method of the Marquis de Condorcet that algortihmes implementing more complex way its criteria.   
But Condorcet Class allows much more than this, and is actually a real manager of election and voting; providing you with powerful management features and your elections storage facilities.

**Create by:** Julien Boudry (born 22/10/1988 - France) [@JulienBoudry](https://twitter.com/JulienBoudry) - _([complete list of contributors](https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class/graphs/contributors))_     
**License:** MIT _(read de LICENSE file at the root folder)_  Including code, examples, logo and documentation     
As a courtesy, I will thank you to inform me about your project wearing this code, produced with love and selflessness. **You can also offer me a bottle of good wine**.   
**Or finance my studies:** *1FavAXcAU5rNkfDDTgMs4xx1FNzDztwYV6*


### Project State
To date, we have a stable version.  
- Since version 0.9, an important work of code review and testing was conducted by the creator.
- Since version 0.15, significant structural changes have strong evolutionary implementation of the API. Including a full object management of the Votes and Candidate, in addition to the old and easier string conceptualization.

**External testers are more than welcome**.   


- To date, the library is used by [Gustav Mahler blind listening test](http://classik.forumactif.com/t7244-ecoute-comparee-mahler-2e-symphonie-la-suite).   
http://gilles78.artisanat-furieux.net/Condorcet/
- From August 2014: [Condorcet-Vote.org](http://www.condorcet-vote.org) opens with a beta version. It is based in large part on this project, and uses the library as a real election manager for computing, storage & stats.   

#### Specifications and standards  
**Stable Version: 0.15**  
**PHP Requirement:** PHP 5.5 with Ctype, MB_String, Json common extensions. _(tested up to PHP 5.6)_

**Autoloading:** This project is consistent with the standard-PSR 0 and can be loaded easily and without modification in most frameworks. Namespace \Condorcet is used. 
The literature also provides easy example of free implementation with or without autoloader.

**Coding standards:** The code is very close to the respect of PSR-1 (lacks only the naming of methods), and freely influenced by PSR-2 when it is not unnecessarily authoritarian.  


#### Related projects
* [Condorcet-Vote.org](http://www.condorcet-vote.org) Web services to create and store online Condorcet election. Including intérractives and collaborative features.   
* [Condorcet API](https://github.com/julien-boudry/Condorcet_API) Very basic and simple http API for Condorcet class (json or text i/o)
* [Mahler-S2-BlindTest-Condorcet
](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet) (french interface) Web wrapper to compute and show result for classical music blind challenge with the Condorcet Class full potential (can also be used and adapted for any elections). Look like the examples provided here, but better.    

---------------------------------------

## Key Features

### Presentation

- Advanced Management for votes and candidates. Support both simple calls or full object management.
- Voting management and inter-elections candidates. Many input supported (array, Json, object, string representation...)
- Computing Pairwise, Natural Condorcet Winner or Loser
- Algorithms implements (fully or partially) the criteria of the Marquis de Condorcet.
- Support for storing elections
- Ready to be extend, or support your own algorithms.
- [...]
  

### Supported Condorcet Methods

*[More information on Condorcet Wiki](https://github.com/julien-boudry/Condorcet/wiki/I-%23-Installation-%26-Basic-Configuration-%23-2.-Condorcet-Methods)*

* **Condorcet Basic** Give you the natural winner or looser of Condorcet, if there is one.  
*(This method is the only core method, you cannot remove it)*

* **Copeland** http://en.wikipedia.org/wiki/Copeland%27s_method

* **Kemeny-Young** http://en.wikipedia.org/wiki/Kemeny-Young_method   
*Kemeny-Young is currently limited to elections not exédant 6 candidates. For reasons of performance almost insuperable. Solutions for a populated cache precalculated data are under review to reach 7 or 8 candidates, and maybe even 9.*

* **Minimax Family** http://en.wikipedia.org/wiki/Minimax_Condorcet
    * **Minimax Winning** *(Does not satisfy the Condorcet loser criterion)*  
    * **Minimax Margin** *(Does not satisfy the Condorcet loser criterion)*
    * **Minimax Opposition**:warning: *By nature, this alternative does not meet any criterion of Condorcet.*

* **RankedPairs *(Since v0.10, EXPERIMENTAL)*** https://en.wikipedia.org/wiki/Ranked_pairs  

* **Schulze Family** http://en.wikipedia.org/wiki/Schulze_method
    * **Schulze** Schulze Winning is recommended by Markus Schulze himself. ***This is the default choice.***
    * **Schulze Margin**
    * **Schulze Ratio**


#### Add new one?	
This class is designed to be easily extensible with new algorithms. A modular schematic is already used for all algorithms provided, so you can easily help, do not forget to make a pull request!  
[*More explanations in the documentation below*](#newAlgo)  


### Roadmap for futher releases 
  
  - Better cache system to prevent any full computing of the Pairwise on new vote / remove vote
  - Ability to add Candidate on Vote
  - Improve & test Ranked pair implementation *(help needed!)*
  - **Looking for testers!**   
 

---------------------------------------
## How to use it?

### Condorcet Wiki Manual

* **[Visit the Manual](https://github.com/julien-boudry/Condorcet/wiki)**

Living and learning examples, giving an overview but not exhaustive of the possibilities of the library.

### Class & Methos reference

The precise documentation of methods is not a wiki. It can be found in the form of Markdown in the "doc" folder for each release.   
* [Class & Methods documentation](doc/)


### Examples

#### Officials examples

* [Visual simple & advanced script examples with HTML output](examples/examples-with-html/)


#### Condorcet Class Implementation

_This example of implementation in others project can very nice or strange... They can be current, or otherwise affect older versions of Condorcet._   

* [An extremely minimalist HTTP API calculating the results of Condorcet.](https://github.com/julien-boudry/Condorcet_API)
* [Gustav Mahler fans, making comparative blind test](https://github.com/julien-boudry/Mahler-S2-BlindTest-Condorcet)


### Quick overview

* [Non-visual quick tour of opportunities without interface](examples/Quick_Overview.php) (not exhaustive and partial)

