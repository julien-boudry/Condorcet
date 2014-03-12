<?php
/*
	Example : How to simply extend the Condorcet Class with your own methods !

	By Julien Boudry - MIT LICENSE (Please read LICENSE.txt)
	https://github.com/julien-boudry/Condorcet_Schulze-PHP_Class 
*/

class MyNewMethod extends Condorcet
{
	protected static $_extend_auth_methods = 'MyNewMethod1,MyNewMethod2' ;


	// Your own results related attributs (compute, final score ...)

		/* ... */


	// Your others attributes if you need it

		/* ... */



	// Only if you need your own constructor
	   public function __construct() 
	   {
  			 parent::__construct();

  			 // Your time
			}




	// Clean our own variable when the Condorcet class need to compute again the résult (after a new vote ...)
	protected function extend_cleanup_result ()
	{
		// You will need to reset your own results attributs
	}



	// Public compute methods





	// Other methods


}
