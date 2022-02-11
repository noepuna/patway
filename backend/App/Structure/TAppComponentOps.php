<?php

/**
 * ...
 *
 * @category	App\Structure
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
	namespace App\Structure;

	use App\IConfig;

	trait TAppComponentOps
	{
		public function t_appComponentOps_exists( IConfig $CONFIG, string $ID ) : bool
		{
			$db = $CONFIG->getDbAdapter();

			$db->query( "SELECT `uid` FROM `app_component` WHERE `uid` = :id", [ 'id' => $ID ] );

			return !!$db->query_num_rows();
		}





		protected function t_appComponentOps_isAvailable( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $ID ];

			$db->query( "SELECT `uid` FROM `app_component` WHERE `uid` = :uid AND `enabled` = 1 LIMIT 0, 1", $params );

			return $db->query_num_rows() === 1;
		}





		public static function t_appComponentOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT uid, structure_fk, name, enabled
				FROM `app_component` C
				WHERE C.uid = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($component)
			{
				return
				[
					'id' => $component['uid'],
					'name' => $component['name'],
					'structure_id' => $component['structure_fk'],
					'enabled' => $component['enabled'] == 1 || $component['enabled'] === chr(0x01)
				];
			};

			if( $db->query_num_rows() )
			{
				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}
	}

?>