<?php

	/**
	 * ...
	 *
	 * @category	App\Messaging
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Messaging;





	use App\IConfig;

	trait TMessagingOps
	{
		public function t_messagingOps_exists( IConfig $CONFIG, string $ID, string $CREATED_BY = NULL ) : bool
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			if( $CREATED_BY )
			{
				$params['created_by'] = $CREATED_BY;

				$db->query( "SELECT `uid` FROM `message` WHERE `uid` = :id AND `created_by` = :created_by LIMIT 0, 1", $params );
			}
			else
			{
				$db->query( "SELECT `uid` FROM `message` WHERE `uid` = :id LIMIT 0, 1", $params );
			}

			return !!$db->query_num_rows();
		}





		protected function t_messagingOps_isAvailable( IConfig $CONFIG, string $MESSAGE_ID, string $ACCOUNT_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $MESSAGE_ID, 'recipient' => $ACCOUNT_ID, 'created_by' => $ACCOUNT_ID ];

			$db->query
			("
				SELECT MSG.uid
				FROM `message` MSG
				LEFT JOIN `message_recipients` RCPT ON RCPT.message_fk = MSG.uid
				WHERE MSG.uid = :uid AND (RCPT.recipient_fk = :recipient OR MSG.created_by = :created_by) AND MSG.deleted = 0
				LIMIT 0, 1",

				$params
			);

			return !!$db->query_num_rows();
		}





		public function t_messagingOps_sendTypeExists( IConfig $CONFIG, string $SEND_TYPE_ID ) : bool
		{
			$db = $CONFIG->getDbAdapter();

			$db->query( "SELECT `uid` FROM `message_type` WHERE `uid` = :id LIMIT 0, 1", [ 'id' => $SEND_TYPE_ID ] );

			return !!$db->query_num_rows();
		}





		public static function t_messagingOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT MSG.uid, MSG.message_type_fk, MSG.created_by, MSG.date_created, MSG.date_updated, MSG.deleted
				FROM `message` MSG
				WHERE MSG.uid = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function( $msg )
			{
				return
				[
					'id' => $msg['uid'],
					'type' => $msg['message_type_fk'],
					'created_by' => $msg['created_by'],
					'date_created' => $msg['date_created'],
					'date_updated' => $msg['date_updated'],
					'deleted' => $msg['deleted'] == 1 || $msg['deleted'] === chr(0x01)
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