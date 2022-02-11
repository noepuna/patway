<?php

/**
 * ...
 *
 * @category	App\Messaging
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\Messaging;





final Class Recipient implements \App\Messaging\IRecipient
{
	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 *
	 *	...
	 */
	public function __construct()
	{

	}





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 *
	 *	...
	 */
	public function __set( $name, $value )
	{
		switch( $name )
		{
			case "id":
				if( !is_string($value) )
				{
					return;
				}
			break;

			case "deleted":
				if( !is_bool($value) )
				{
					return;
				}
			break;
		}

		!($this->$name ?? null) && $this->$name = $value;
	}





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 */
	public function getId() :? string
	{
		return $this->id ?? null;
	}





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 */
	public function isDeleted() : bool
	{
		return $this->deleted ?? false;
	}
}

?>