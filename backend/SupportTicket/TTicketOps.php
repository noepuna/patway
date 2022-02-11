<?php

/**
 * ...
 *
 * @category	App\SupportTicket
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\SupportTicket;

	use App\IConfig;

	trait TTicketOps
	{
		protected function t_ticketOps_exists( IConfig $CONFIG, string $TICKET_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $TICKET_ID ];

			$db->query("SELECT `uid` FROM `support_ticket` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_ticketOps_isAvailable( IConfig $CONFIG, string $TICKET_ID, string $CREATOR = null )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $TICKET_ID, 'deleted' => 0 ];

			if( $CREATOR )
			{
				$params['created_by'] = $CREATOR;
			}

			foreach( $params as $field => $value )
			{
				$whereFrag[] = "`{$field}` = :{$field}";
			}

			$whereFrag = " WHERE " . implode(" AND ", $whereFrag);

			$db->query("SELECT `uid` FROM `support_ticket` {$whereFrag} LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_ticketOps_categoryIsAvailable( IConfig $CONFIG, string $CATEGORY_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $CATEGORY_ID ];

			$db->query("SELECT `uid` FROM `support_ticket_category` WHERE `uid` = :id AND `disabled` = 0 LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_ticketOps_severityIsAvailable( IConfig $CONFIG, string $SEVERITY_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $SEVERITY_ID ];

			$db->query("SELECT `uid` FROM `support_ticket_severity` WHERE `uid` = :id AND `disabled` = 0 LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_ticketOps_statusIsAvailable( IConfig $CONFIG, string $STATUS_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $STATUS_ID ];

			$db->query("SELECT `uid` FROM `support_ticket_status` WHERE `uid` = :id AND `disabled` = 0 LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		public static function t_ticketOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT T.uid, T.category_fk, T.description, T.severity_fk, T.status_fk, T.created_by, T.date_created, T.deleted 
				FROM `support_ticket` T
				WHERE T.uid = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($ticket)
			{
				return
				[
					'id' => $ticket['uid'],
					'category' => $ticket['category_fk'],
					'description' => $ticket['description'],
					'severity' => $ticket['severity_fk'],
					'status' => $ticket['status_fk'],
					'created_by' => $ticket['created_by'],
					'date_created' => $ticket['date_created'],
					'deleted' => $ticket['deleted'] == 1 ||  $ticket['deleted'] === chr(0x01)
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