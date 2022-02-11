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

	trait TUtilFileOps
	{
		public static function t_utilFileOps_isIcon( $FILE_PATH ) : bool
		{
			return preg_match("/\.ico$/i", $FILE_PATH, $outputArray);
		}





		public static function t_utilFileOps_hasExtension( $FILE_PATH, string ...$EXPECTED_EXTENSIONS ) : bool
		{
			$ext = pathinfo($FILE_PATH, PATHINFO_EXTENSION);

			return in_array($ext, $EXPECTED_EXTENSIONS);
		}
	}

?>