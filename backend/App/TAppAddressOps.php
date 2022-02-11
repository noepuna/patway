<?php

/**
 * ...
 *
 * @category	App
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
	namespace App;

	use App\IConfig;

	trait TAppAddressOps
	{
		public function t_AppAddressOps_typeExists( IConfig $CONFIG, string $TYPE ) : bool
		{
			$db = $CONFIG->getDbAdapter();

			$db->query("SELECT `uid` FROM `address_type` WHERE `uid` = :type", [ 'type' => $TYPE ]);

			return !!$db->query_num_rows();
		}
	}

?>