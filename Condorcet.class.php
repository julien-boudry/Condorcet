<?php



class Condorcet
{

	// Paramètrage
	protected $_method ;
	protected $_options ;
	protected $_votes ;

	// Mécanique
	protected $_i_option_id = 'A' ;



	// Constructor

	public function __construct ($method = 'Schulze')
	{
		$this->_method = $method ;

		$this->_options = array() ;
	}

	public function set_method ($method)
	{
		$this->_method = $method ;
	}


	// Register vote option

	public function add_option ($option_id = null)
	{
		
		// Filtrage
		if ( !is_null($option_id) && !is_string($option_id) && !is_int($option_id) )

			{ return "error : Mauvaise saisie de l'option" ; }

		
		// Process
		if ( empty($option_id) ) // Option_id is empty ...
		{

			while ( !$this->try_add_option($this->_i_option_id) )
			{
				$this->_i_option_id++ ;
			}

			$this->_options[] = $this->_i_option_id ;

			return TRUE ;

		}
		else // Try to add the option_id
		{

			if ( $this->try_add_option($option_id) )
			{
				$this->_options[] = $option_id ;

				return TRUE ;
			}
			else
			{
				return 'error : Option déjà existante' ;
			}

		}

	}

			protected function try_add_option ($option_id)
			{
				return !in_array($option_id, $this->_options) ;
			}



	// Register votes



}