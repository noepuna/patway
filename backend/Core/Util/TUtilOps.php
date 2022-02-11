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

	trait TUtilOps
	{
		public static function t_UtilOps_classWithBackslash()
		{
			return "\\" . static::class;
		}

		public static function t_UtilOps_print( $data, $color = "#333" )
		{
			echo "<div style='border:1px solid #000;padding:20px;margin:10px 0px;'><b style='color:blue;'>Print Log:</b>";
			if( is_null($data) )
			{
				echo " <span style='color:{$color};'>null</span>";
			}
			else
			{
				echo "<pre style='color:{$color};'>" . print_r($data, 1) . "</pre>";
			}

			echo "</div>";
		}
	}

?>