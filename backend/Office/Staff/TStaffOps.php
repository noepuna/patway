<?php

/**
 * ...
 *
 * @category	App\Office\Staff
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Office\Staff;

	use App\IConfig;

	trait TStaffOps
	{
		public function t_StaffOps_isAvailable( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$params = [ 'account_id' => $ACCOUNT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT OF_S.auth_fk
				FROM `office_staff` OF_S
				WHERE OF_S.auth_fk = :account_id AND OF_S.deleted = 0 LIMIT 0, 1",

				$params
			);

			return 1 === $db->query_num_rows();
		}

		public function t_StaffOps_getInfoById( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$params = [ 'account_id' => $ACCOUNT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT OF_S.created_by, OF_S.office_fk, OF_S.deleted
				FROM `office_staff` OF_S
				WHERE OF_S.auth_fk = :account_id LIMIT 0, 1",

				$params
			);

			if( 1 === $db->query_num_rows() )
			{
				$mapFn = function($data)
				{
					$entry =
					[
						'created_by' => $data['created_by'],
						'office' => $data['office_fk'],
						'deleted' => $data['deleted']
					];

					return $entry;
				};

				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}
	}

?>