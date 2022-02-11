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
	use App\Auth\AuthPrevilegeMeta;

	trait TOwnerOps
	{
		public function t_OwnerOps_exists( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'auth_fk' => $ACCOUNT_ID ];

			$db->query("SELECT `auth_fk` FROM `office` WHERE `auth_fk` = :auth_fk LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}

		public function t_OwnerOps_nameExists( IConfig $CONFIG, string $NAME )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'name' => $NAME ];

			$db->query("SELECT `name` FROM `office` WHERE `name` = :name LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}

		public function t_OwnerOps_isAvailable( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ACCOUNT_ID, 'office_previlege' => AuthPrevilegeMeta::OFFICE_OWNER ];

			$db->query
			("
				SELECT `uid`
				FROM `auth` A
				INNER JOIN `office` OF ON A.uid = OF.auth_fk
				INNER JOIN `account_previleges` AC_P ON A.uid = AC_P.auth_fk
				WHERE A.uid = :id AND A.enabled = 1 AND AC_P.previlege_fk = :office_previlege LIMIT 0, 1",

				$params
			);

			return $db->query_num_rows() === 1;
		}

		public function t_OwnerOps_getInfoById( IConfig $CONFIG, string $ACCOUNT_ID )
		{
			$params = [ 'account_id' => $ACCOUNT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT OF.name, OF.admin_fk
				FROM `office` OF
				WHERE OF.auth_fk = :account_id LIMIT 0, 1",

				$params
			);

			if( 1 === $db->query_num_rows() )
			{
				$mapFn = function($data)
				{
					$entry =
					[
						'name' => $data['name'],
						'admin' => $data['admin_fk']
					];

					return $entry;
				};

				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}
	}

?>