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

	trait TAppAccessModifierOps
	{
		public static function t_AppAccessModifierOps_exists( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();

			$db->query("SELECT `uid` FROM `access_modifier` WHERE `uid` = :uid LIMIT 0, 1", [ 'uid' => $ID ]);

			return !!$db->query_num_rows();
		}
	}

?>