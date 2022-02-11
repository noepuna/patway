<?php

/**
 * ...
 *
 * @category	App\HashTag
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\HashTag;

	use App\IConfig;





	trait THashTagOps
	{
		protected function t_HashTagOps_exists( IConfig $CONFIG, string $HASHTAG )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'name' => $HASHTAG ];

			$db->query("SELECT `uid` FROM `hash_tag` WHERE `name` = :name LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_HashTagOps_isAvailable( IConfig $CONFIG, string $HASHTAG )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'name' => $HASHTAG, 'disabled' => 1 ];

			$db->query("SELECT `uid` FROM `hash_tag` WHERE `name` = :name AND `disabled` = :disabled LIMIT 0, 1", $params);

			return $db->query_num_rows() === 0;
		}





		public static function t_HashTagOps_getInfo( IConfig $CONFIG, string $HASHTAG )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'name' => $HASHTAG ];

			$db->query
			("
				SELECT HT.uid, HT.name, HT.date_created, HT.disabled
				FROM `hash_tag` HT
				WHERE HT.name = :name
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($hashtag)
			{
				return
				[
					'id' => $hashtag['uid'],
					'name' => $hashtag['name'],
					'date_created' => $hashtag['date_created'],
					'disabled' => $hashtag['disabled'] == 1 || $hashtag['disabled'] === chr(0x01)
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