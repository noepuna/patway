<?php

/**
 * ...
 *
 * @category	App\Office\Department
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Office\Department;

	use App\IConfig;





	trait TOfficeDepartmentOps
	{
		public function t_OfficeDepartmentOps_exists( IConfig $CONFIG, string $DEPARTMENT_ID, string $ADMIN_ID = null )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $DEPARTMENT_ID ];

			if( $ADMIN_ID )
			{
				$params['admin'] = $ADMIN_ID;

				$db->query("SELECT `uid` FROM `office_department` WHERE `uid` = :id AND `admin_fk` = :admin LIMIT 0, 1", $params);
			}
			else
			{
				$db->query("SELECT `uid` FROM `office_department` WHERE `uid` = :id LIMIT 0, 1", $params);
			}

			return $db->query_num_rows() === 1;
		}





		public function t_OfficeDepartmentOps_isAvailable( IConfig $CONFIG, string $DEPARTMENT_ID, string $ADMIN_ID = null )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $DEPARTMENT_ID ];

			if( $ADMIN_ID )
			{
				$params['admin'] = $ADMIN_ID;

				$db->query
				("
					SELECT `uid`
					FROM `office_department`
					WHERE `uid` = :id AND `admin_fk` = :admin AND `enabled` = 1
					LIMIT 0, 1",

					$params
				);
			}
			else
			{
				$db->query("SELECT `uid` FROM `office_department` WHERE `uid` = :id AND `enabled` = 1 LIMIT 0, 1", $params);	
			}

			return $db->query_num_rows() === 1;
		}





		public function t_OfficeDepartmentOps_nameExists( IConfig $CONFIG, string $NAME, string $ADMIN_ID = NULL, $DEPARTMENT_ID = NULL )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'name' => $NAME ];

			if( $ADMIN_ID )
			{
				$params['admin_fk'] = $ADMIN_ID;
			}

			$whereSQLFrag;

			foreach( $params as $column => $value )
			{
				$whereSQLFrag[] = "`{$column}` = :{$column}";
			}

			$whereSQLFrag = implode(" AND ", $whereSQLFrag);

			if( $DEPARTMENT_ID )
			{
				$params += [ 'uid' => $DEPARTMENT_ID ];

				$whereSQLFrag .= " AND `uid` != :uid";
			}

			$db->query("SELECT `name` FROM `office_department` WHERE {$whereSQLFrag} LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		public function t_OfficeDepartmentOps_getInfoById( IConfig $CONFIG, string $DEPARTMENT_ID )
		{
			$params = [ 'department_id' => $DEPARTMENT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT DEPT.name, DEPT.description, DEPT.admin_fk, DEPT.enabled
				FROM `office_department` DEPT
				WHERE DEPT.uid = :department_id LIMIT 0, 1",

				$params
			);

			if( 1 === $db->query_num_rows() )
			{
				$mapFn = function($data)
				{
					$entry =
					[
						'name' => $data['name'],
						'description' => $data['description'],
						'admin' => $data['admin_fk'],
						'enabled' => $data['enabled'] == 1 || $data['enabled'] === chr(0x01)
					];

					return $entry;
				};

				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}
	}

?>