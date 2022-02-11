<?php

/**
 * ...
 *
 * @category	App\Construction\EnvironmentHealthSafety
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Construction\EnvironmentHealthSafety;

	use App\IConfig;

	trait TEHSOps
	{
		protected function t_EHSOps_exists( IConfig $CONFIG, string $EHS_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $EHS_ID ];

			$db->query("SELECT `uid` FROM `environment_health_safety` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_EHSOps_isAvailable( IConfig $CONFIG, string $EHS_ID, string $CREATOR = null )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $EHS_ID, 'deleted' => 0 ];

			if( $CREATOR )
			{
				$params['created_by'] = $CREATOR;
			}

			foreach( $params as $field => $value )
			{
				$whereFrag[] = "`{$field}` = :{$field}";
			}

			$whereFrag = " WHERE " . implode(" AND ", $whereFrag);

			$db->query("SELECT `uid` FROM `environment_health_safety` {$whereFrag} LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_EHSOps_nameExists( IConfig $CONFIG, string $EHS_NAME, string $CREATOR, $EHS_ID = NULL )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'name' => $EHS_NAME, 'created_by' => $CREATOR ];

			if( $EHS_ID )
			{
				$params += [ 'uid' => $EHS_ID ];

				$db->query
				("
					SELECT `uid`
					FROM `environment_health_safety`
					WHERE `name` = :name AND `created_by` = :created_by AND `uid` != :uid",

					$params
				);
			}
			else
			{
				$db->query
				("
					SELECT `uid`
					FROM `environment_health_safety`
					WHERE `name` = :name AND `created_by` = :created_by",

					$params
				);
			}

			return $db->query_num_rows() === 1;
		}





		protected function t_EHSOps_isAvailableByMember( IConfig $CONFIG, string $EHS_ID, string $MEMBER )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $EHS_ID, 'member' => $MEMBER ];

			$db->query
			("
				SELECT `uid`
				FROM `environment_health_safety` EHS
				INNER JOIN `office_staff` OFC_STAFF ON EHS.created_by = OFC_STAFF.created_by
				WHERE EHS.uid = :uid AND EHS.enabled = 1 AND EHS.deleted = 0 AND OFC_STAFF.auth_fk = :member
				LIMIT 0, 1",

				$params
			);

			return $db->query_num_rows() === 1;
		}





		public static function t_EHSOps_getInfoById( IConfig $CONFIG, string $EHS_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $EHS_ID ];

			$db->query
			("
				SELECT EHS.uid, EHS.name, EHS.description, EHS.icon, EHS.attachment, EHS.created_by, EHS.date_created,
				EHS.enabled, EHS.deleted
				FROM `environment_health_safety` EHS
				WHERE EHS.uid = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($ehs)
			{
				return
				[
					'id' => $ehs['uid'],
					'name' => $ehs['name'],
					'description' => $ehs['description'],
					'icon' => $ehs['icon'],
					'attachment' => $ehs['attachment'],
					'created_by' => $ehs['created_by'],
					'date_created' => $ehs['date_created'],
					'enabled' => $ehs['enabled'] == 1 ||  $ehs['enabled'] === chr(0x01),
					'deleted' => $ehs['deleted'] == 1 ||  $ehs['deleted'] === chr(0x01)
				];
			};

			if( $db->query_num_rows() )
			{
				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}





		public static function t_EHSOps_getSettingsById( IConfig $CONFIG, string $EHS_ID )
		{

			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $EHS_ID ];

			$db->query
			("
				SELECT STG.has_event
				FROM `environment_health_safety_settings` STG
				WHERE STG.ehs_fk = :id
				LIMIT 0, 1",

				$params
			);

			$mapFn = function($ehs)
			{
				return
				[
					'has_event' => $ehs['has_event'] == 1 ||  $ehs['has_event'] === chr(0x01)
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