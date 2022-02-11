<?php

/**
 * ...
 *
 * @category	App\Account
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Account;

	use App\IConfig;

	trait TAccountOps
	{
		public static function t_AccountOps_exists( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'auth_fk' => $ACCOUNT_ID ];

			$db->query("SELECT `auth_fk` FROM `account_basic_information` WHERE `auth_fk` = :auth_fk LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}

		public static function t_AccountOps_isAvailable( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ACCOUNT_ID ];

			$db->query
			("
				SELECT A.uid
				FROM `auth` A
				INNER JOIN `account_basic_information` A_BI ON A.uid = A_BI.auth_fk
				WHERE A.uid = :id AND A.disabled = 0 AND A.status_fk = 1 LIMIT 0, 1",

				$params
			);

			return $db->query_num_rows() === 1;
		}

		public static function t_AccountOps_getInfoById( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$params = [ 'account_id' => $ACCOUNT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT A.status_fk AS `status`,
				A_BA.firstname, A_BA.middlename, A_BA.lastname, A_BA.email,
				A_BA.tel_num AS `contact_number`, A_BA.address AS `physical_address`, A_BA.date_joined
				FROM `auth` A 
				INNER JOIN `account_basic_information` A_BA ON A.uid = A_BA.auth_fk
				WHERE A.uid = :account_id LIMIT 0, 1",

				$params
			);

			if( 1 === $db->query_num_rows() )
			{
				$mapFn = function($data)
				{
					$entry =
					[
						'firstname' => $data['firstname'],
						'middlename' => $data['middlename'],
						'lastname' => $data['lastname'],
						'address' => $data['physical_address'],
						'email' => $data['email'],
						'contact_number' => $data['contact_number'],
						'status' => $data['status'],
						'date_joined' => $data['date_joined']
					];

					return $entry;
				};

				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}
	}

?>