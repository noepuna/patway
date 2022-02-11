<?php

/**
 * ...
 *
 * @category	App\Office
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Office;

	use App\IConfig;





	trait TOfficeOps
	{
		public function t_officeOps_isMember( IConfig $CONFIG, string $STAFF, string $CREATOR )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'creator' => $CREATOR, 'staff' => $STAFF ];

			$db->query
			("
				SELECT `auth_fk`
				FROM `office_staff`
				WHERE `auth_fk` = :staff AND `created_by` = :creator",

				$params
			);

			return $db->query_num_rows();
		}





		public function t_officeOps_isCoworker( IConfig $CONFIG, string $STAFF1, string $STAFF2 )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'staff_1' => $STAFF1, 'staff_2' => $STAFF2 ];

			$db->query
			("
				SELECT STAFF1.auth_fk
				FROM `office_staff` AS STAFF1
				LEFT JOIN `office_staff` AS STAFF2 ON STAFF1.created_by = STAFF2.created_by
				WHERE STAFF1.auth_fk = :staff_1 AND STAFF2.auth_fk = :staff_2",

				$params
			);

			return !!$db->query_num_rows();
		}
	}

?>