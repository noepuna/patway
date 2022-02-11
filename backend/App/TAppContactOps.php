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

	trait TAppContactOps
	{
		public static function t_AppContactOps_typeExists( IConfig $CONFIG, string $TYPE )
		{
			$db = $CONFIG->getDbAdapter();

			$db->query("SELECT `uid` FROM `contact_type` WHERE `uid` = :uid LIMIT 0, 1", [ 'uid' => $TYPE ]);

			return !!$db->query_num_rows();
		}

		public function t_AppContactOps_exists( IConfig $CONFIG, string $TYPE, string $CONTACT )
		{
			$db = $CONFIG->getDbAdapter();
			$params =
			[
				'type' => $TYPE,
				'contact' => $CONTACT
			];

			$db->query("SELECT `contact` FROM `account_contacts` WHERE `contact_type_fk` = :type AND `contact` = :contact", $params);

			return !!$db->query_num_rows();
		}
	}

?>