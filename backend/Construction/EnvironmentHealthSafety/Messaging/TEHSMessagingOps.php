<?php

	/**
	 * ...
	 *
	 * @category	App\Construction\EnvironmentHealthSafety\Messaging
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Construction\EnvironmentHealthSafety\Messaging;





	use App\IConfig;

	trait TEHSMessagingOps
	{
		public function t_EHSMessagingOps_exists( IConfig $CONFIG, string $ID ) : bool
		{
			$db = $CONFIG->getDbAdapter();

			$db->query( "SELECT `message_fk` FROM `environment_health_safety_messages` WHERE `message_fk` = :id LIMIT 0, 1", [ 'id' => $ID ] );

			return !!$db->query_num_rows();
		}





		protected function t_EHSMessagingOps_statusExists( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $ID ];

			$db->query( "SELECT `uid` FROM `environment_health_safety_message_status` WHERE `uid` = :uid LIMIT 0, 1", $params );

			return $db->query_num_rows() === 1;
		}





		protected function t_EHSMessagingOps_riskLevelExists( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $ID ];

			$db->query( "SELECT `uid` FROM `environment_health_safety_risk_level` WHERE `uid` = :uid LIMIT 0, 1", $params );

			return $db->query_num_rows() === 1;
		}





		public static function t_EHSMessagingOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT `message_fk`, `status_fk`, `ehs_fk`, `title`, `location`, `description`, `risk_level_fk`, `date_start`, `date_end`
				FROM `environment_health_safety_messages`
				WHERE `message_fk` = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function( $data )
			{
				return
				[
	    			'ehs_id' => $data['ehs_fk'],
	    			'status_id' => $data['status_fk'],
					'title' => $data['title'],
					'location' => $data['location'],
					'risk_level_id' => $data['risk_level_fk'],
					'date_start' => $data['date_start'],
					'date_end' => $data['date_end'],
					'description' => $data['description']
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