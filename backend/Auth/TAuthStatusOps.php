<?php

/**
 * ...
 *
 * @category	App\Auth
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Auth;

	use App\IConfig;

	trait TAuthStatusOps
	{
		public static function t_AuthStatusOps_exists( IConfig $CONFIG, string $STATUS_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $STATUS_ID ];

			$db->query("SELECT `uid` FROM `auth_status` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}

		public static function t_AuthStatusOps_search( IConfig $CONFIG, Array $FILTER = NULL )
		{
			$db = $CONFIG->getDbAdapter();

			$db->query("SELECT `uid`, `label` FROM `auth_status`");

			$mapFn = function(&$row)
			{
				$row = [ 'id' => $row['uid'], 'label' => $row['label'] ];
			};

			return $db->fetch($mapFn);
		}
	}

?>