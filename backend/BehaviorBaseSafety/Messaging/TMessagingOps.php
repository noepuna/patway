<?php

	/**
	 * ...
	 *
	 * @category	App\BehaviorBaseSafety\Messaging
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\BehaviorBaseSafety\Messaging;





	use App\IConfig;

	trait TMessagingOps
	{
		/*public function t_appFileOps_exists( IConfig $CONFIG, string $ID ) : bool
		{
			$db = $CONFIG->getDbAdapter();

			$db->query( "SELECT `uid` FROM `app_files` WHERE `uid` = :id LIMIT 0, 1", [ 'id' => $ID ] );

			return !!$db->query_num_rows();
		}





		protected function t_appFileOps_isAvailable( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $ID ];

			$db->query( "SELECT `uid` FROM `app_files` WHERE `uid` = :uid AND `deleted` = 0 LIMIT 0, 1", $params );

			return $db->query_num_rows() === 1;
		}*/





		/*public static function t_appFileOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT `uid`, `visibility_fk`, `app_component_fk`, `title`, `description`, `url_path`, `created_by`, `date_created`, `deleted`
				FROM `app_files` F
				WHERE F.uid = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($file)
			{
				return
				[
					'id' => $file['uid'],
					'visibility_id' => $file['visibility_fk'],
					'app_component_id' => $file['app_component_fk'],
					'title' => $file['title'],
					'description' => $file['description'],
					'url_path' => $file['url_path'],
					'created_by' => $file['created_by'],
					'date_created' => $file['date_created'],
					'deleted' => $file['deleted'] == 1 || $file['deleted'] === chr(0x01)
					
				];
			};

			if( $db->query_num_rows() )
			{
				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}*/
	}

?>