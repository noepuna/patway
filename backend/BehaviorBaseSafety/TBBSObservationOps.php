<?php

/**
 * ...
 *
 * @category	App\BehaviorBaseSafety
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\BehaviorBaseSafety;

	use App\IConfig;





	trait TBBSObservationOps
	{
		protected function t_BBSObservationOps_exists( IConfig $CONFIG, string $OBSERVATION_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $OBSERVATION_ID ];

			$db->query("SELECT `uid` FROM `bbs_observation` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_BBSObservationOps_isAvailable( IConfig $CONFIG, string $OBSERVATION_ID, string $ACCOUNT_ID = null, bool $IS_CREATOR_ONLY = true )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $OBSERVATION_ID, 'deleted' => 0 ];

			if( $ACCOUNT_ID )
			{
				$whereFrag = "O.created_by = :created_by";
				$params['created_by'] = $ACCOUNT_ID;

				if( !$IS_CREATOR_ONLY )
				{
					$params['supervisor'] = $ACCOUNT_ID;
					$params['observer'] = $ACCOUNT_ID;
					$params['co_worker'] = $ACCOUNT_ID;

					$whereFrag = "(O.created_by = :created_by OR O.observer = :observer OR O.supervisor = :supervisor OR OFC_STAFF.auth_fk = :co_worker)";
				}
			}

			$whereFrag = $whereFrag ?? 1;

			$db->query
			("
				SELECT O.uid
				FROM `bbs_observation` O
				INNER JOIN `office_staff` OFC_STAFF_JUNCTION ON OFC_STAFF_JUNCTION.auth_fk = O.created_by
				INNER JOIN `office_staff` OFC_STAFF ON OFC_STAFF.created_by = OFC_STAFF_JUNCTION.created_by
				WHERE O.uid = :uid AND O.deleted = :deleted AND {$whereFrag}
				GROUP BY O.uid
				LIMIT 0, 1",

				$params
			);

			return $db->query_num_rows() === 1;
		}





		public static function t_BBSObservationOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT O.uid, O.visibility_fk, O.observer, O.supervisor, O.notes, O.recommendation, O.action_taken,
				O.feedback_to_coworkers, O.attachment_file, O.created_by, O.date_created, O.deleted 
				FROM `bbs_observation` O
				WHERE O.uid = :id
				LIMIT 0, 1",

				$params
			);

			$basicDetailsFn = function($observation)
			{
				return
				[
					'id' => $observation['uid'],
					'visibility' => $observation['visibility_fk'],
					'observer' => $observation['observer'],
					'supervisor' => $observation['supervisor'],
					'notes' => $observation['notes'],
					'recommendation' => $observation['recommendation'],
					'action_taken' => $observation['action_taken'],
					'feedback_to_coworkers' => $observation['feedback_to_coworkers'],
					'attachment_file' => $observation['attachment_file'],
					'created_by' => $observation['created_by'],
					'date_created' => $observation['date_created'],
					'deleted' => $observation['deleted'] == 1 ||  $observation['deleted'] === chr(0x01)
				];
			};

			$typesFn = function( $data )
			{
				return $data['type_fk'];
			};

			if( $db->query_num_rows() )
			{
				$accountDetails = array_map($basicDetailsFn, $db->fetch())[0];

				$db->query("SELECT `type_fk` FROM `bbs_observation_types` WHERE `observation_fk` = :id AND `deleted` = 0", $params);

				$accountDetails['types'] = array_map($typesFn, $db->fetch());

				return $accountDetails;
			}

			return false;
		}
	}

?>