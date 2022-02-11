<?php

/**
 * ...
 *
 * @category	Core\Util
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
	namespace Core\Util;

	trait TUtilEmailOps
	{
		public static function t_UtilEmailOps_isValid( $EMAIL_ADDRESS )
		{
			return filter_var($EMAIL_ADDRESS, FILTER_VALIDATE_EMAIL);
		}
	}

?>