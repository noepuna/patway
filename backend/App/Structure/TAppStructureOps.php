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

	trait TAppStructureOps
	{
		public function t_appStructureOps_exists( IConfig $CONFIG, string $ID ) : bool
		{
			$db = $CONFIG->getDbAdapter();

			$db->query("SELECT `uid` FROM `app_structure` WHERE `uid` = :id", [ 'id' => $ID ]);

			return !!$db->query_num_rows();
		}





		public static function t_appStructureOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT `uid`, `name`
				FROM `structure` S
				WHERE S.uid = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($event)
			{
				return
				[
					'id' => $event['uid'],
					'name' => $event['name']
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