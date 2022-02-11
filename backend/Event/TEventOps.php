<?php

/**
 * ...
 *
 * @category	App\Event
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Event;

	use App\IConfig;

	trait TEventOps
	{
		protected function t_eventOps_exists( IConfig $CONFIG, string $EVENT_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $EVENT_ID ];

			$db->query("SELECT `uid` FROM `event` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_eventOps_isAvailable( IConfig $CONFIG, string $EVENT_ID, string $CREATOR = null )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $EVENT_ID, 'deleted' => 0 ];

			if( $CREATOR )
			{
				$params['created_by'] = $CREATOR;
			}

			foreach( $params as $field => $value )
			{
				$whereFrag[] = "`{$field}` = :{$field}";
			}

			$whereFrag = " WHERE " . implode(" AND ", $whereFrag);

			$db->query("SELECT `uid` FROM `event` {$whereFrag} LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		public static function t_eventOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT E.uid, E.title, E.description, E.location, E.closed, E.created_by, E.date_created, E.deleted 
				FROM `event` E
				WHERE E.uid = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($event)
			{
				return
				[
					'id' => $event['uid'],
					'title' => $event['title'],
					'description' => $event['description'],
					'location' => $event['location'],
					'closed' => $event['closed'] == 1 ||  $event['closed'] === chr(0x01),
					'created_by' => $event['created_by'],
					'date_created' => $event['date_created'],
					'deleted' => $event['deleted'] == 1 ||  $event['deleted'] === chr(0x01)
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