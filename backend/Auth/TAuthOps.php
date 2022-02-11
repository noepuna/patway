<?php

	/**
	 * ...
	 *
	 * @category	App\Auth
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Auth;

	use App\IConfig;

	trait TAuthOps
	{
		public function t_AuthOps_usernameExists( IConfig $CONFIG, string $USERNAME )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'username' => $USERNAME ];

			$db->query("SELECT `username` FROM `auth` WHERE `username` = :username LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}

		public function t_AuthOps_isCurrentPassword( IConfig $CONFIG, string $ID, string $PASSWORD )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'uid' => $ID ];

			$db->query("SELECT `password` FROM `auth` WHERE `uid` = :uid LIMIT 0, 1", $params);

			if( $db->query_num_rows() === 1 )
			{
				return $db->fetch()[0]['password'] === md5($PASSWORD);
			}

			return false;
		}

		public function t_AuthOps_previlegeExists( IConfig $CONFIG, string $PREVILEGE, string ...$PREVILEGES )
		{
			$db = $CONFIG->getDbAdapter();

			$param = [];
			$PREVILEGES[] = $PREVILEGE;

			foreach( $PREVILEGES as $key => $previlege )
			{
				$param["previlege" . $key] = $previlege;
			}

			$db->query("SELECT `uid` FROM `previlege` WHERE `uid` IN(:" . implode(", :", array_keys($param)) . ")", $param);

			return $db->query_num_rows() === count($PREVILEGES);
		}

		public static function t_AuthOps_getInfoById( IConfig $CONFIG, string $ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $ID ];

			$db->query
			("
				SELECT `status_fk`
				FROM `auth` A 
				WHERE A.uid = :id LIMIT 0, 1",

				$params
			);

			if( 1 === $db->query_num_rows() )
			{
				$mapFn = function($data)
				{
					$entry =
					[
						'status' => $data['status_fk'],
					];

					return $entry;
				};

				return array_map($mapFn, $db->fetch())[0];
			}

			return false;
		}

		public function t_AuthOps_getPrevilegesById( IConfig $CONFIG, string $ID, bool $showDisabled = false ) : Array
		{
			$db = $CONFIG->getDbAdapter();

			$params = [ 'uid' => $ID ];

			$db->query("SELECT `previlege_fk`, `enabled` FROM `auth_previleges` WHERE `auth_fk` = :uid", $params);

			$mapFn = function( &$row )
			{
				$row =
				[
					'previlege' => @$row['previlege_fk'],
					'enabled' => !!$row['enabled']
				];
			};

			return $db->fetch($mapFn);
		}
	}

?>