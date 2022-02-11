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

	trait TDepartmentEntryOps
	{
		public function t_DepartmentEntryOps_getByAccount( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$params = [ 'account_id' => $ACCOUNT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT `department_fk` as `department_id`
				FROM `office_department_staff`
				WHERE `auth_fk` = :account_id AND deleted = 0",

				$params
			);

			return array_map( fn($entry) => $entry['department_id'] , $db->fetch());
		}
	}

?>