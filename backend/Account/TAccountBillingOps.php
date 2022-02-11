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

	trait TAccountBillingOps
	{

		public static function t_AccountBillingOps_getInfoById( IConfig $CONFIG, string $ACCOUNT_ID ) : ?Array
		{
			$params = [ 'account_id' => $ACCOUNT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT A_Bi.firstname, A_Bi.middlename, A_Bi.lastname, A_Bi.email, A_Bi.tel_num AS `contact_number`, A_Bi.address
				FROM `account_billing_information` A_Bi
				WHERE A_Bi.auth_fk = :account_id LIMIT 0, 1",

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
						'address' => $data['address'],
						'email' => $data['email'],
						'contact_number' => $data['contact_number']
					];

					return $entry;
				};

				return array_map($mapFn, $db->fetch())[0];
			}

			return null;
		}
	}

?>