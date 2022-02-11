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

	use DateTime;

	trait TUtilDateOps
	{
		public static function t_UtilDateOps_isValid( string $DATE_STR, string $FORMAT )
		{
			try
			{
			    new DateTime($DATE_STR);

			    return true;
			}
			catch (Exception $e)
			{
			    return false;
			}
		}

		public static function t_UtilDateOps_isFuture( string $DATE_STR )
		{
			if( $this->t_UtilDateOps_isValid($DATE_STR) )
			{
				$iGivenDate = new DateTime($DATE_STR);
				$iNow = new DateTime();

				if( $iGivenDate >= $iNow )
				{
					return true;
				}
			}

			return false;
		}
	}

?>