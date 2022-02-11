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
	use App\Auth\AuthMeta;
	use App\Auth\AuthStatusMeta;

	class Auth implements \App\Auth\IAuth
	{
		use \Core\Util\TUtilOps,
			\App\Auth\TAuthOps;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>
	     * @access private
	     */
	    private $_prop =
	    [
	    	'crud_method' => null,
	    	'id' => null,
	    	'username' => null,
	    	'password' => null,
	    	'status' => null,
	    	'previleges' => [],
	    	'login_token' => null,
	    	'disabled' => null
	    ];





        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'basic' => false, 'previleges' => false ];





		/**
		 * This is the web application's system configuration settings.
		 * 
		 * @var App\IConfig
		 * @access private
		 */
		private IConfig $_config;
	




		/**
		 * This is the cached session id of the current account.
		 *
		 * @var string
		 * @access private
		 * @static
		 */
		private static $_session;





		/**
		 *	...
		 *
		 * @access public
		 * @static
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function __construct( IConfig $CONFIG, AuthMeta $META )
		{
			$d = $META->auth;

			$requirements =
			[
				'create' => [ "crud_method", [ "auth", "username", "password", "re_password" ] ],
				'login' => 	[ "crud_method", [ "auth", "username", "password", "previleges" ] ],
				'read' => 	[ "crud_method", [ "auth", "login_token" ] ],
				'update' => [ "crud_method", [ "auth", "login_token" ] ],
				'read-as-owner' => [ "crud_method", [ "auth", "login_token", "id" ] ]
			];

			$additionalParam = [];

			if( $META->require(...$requirements['create']) && AuthMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
				$additionalParam =
				[
					'crud_method' => $META->crud_method,
					'username' => $d['username'],
					'password' => $d['password'],
					'previleges' => @$d['previleges'] ?$d['previleges'] :[],
					'status' => AuthStatusMeta::UNVERIFIED,
					'disabled' => false
				];
			}
			else if( $META->require(...$requirements['login']) && AuthMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$additionalParam =
				[
					'crud_method' => $META->crud_method,
					'username' => $d['username'],
					'password' => $d['password'],
					'previleges' => @$d['previleges'] ?$d['previleges'] :[]
				];
			}
			else if( $META->require(...$requirements['read-as-owner']) && AuthMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$tokenData = $CONFIG->decrypt($META->auth['login_token']);

				$additionalParam =
				[
					'crud_method' => $META->crud_method,
					'login_token' => $d['login_token'],
					'id' => $META->auth['id'],
					'previleges' => @$d['previleges'] ?$d['previleges'] :[] //'previleges' => $tokenData['account']['previleges']
				];
			}
			else if( $META->require(...$requirements['read']) && AuthMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$tokenData = $CONFIG->decrypt($META->auth['login_token']);

				$additionalParam =
				[
					'crud_method' => $META->crud_method,
					'login_token' => $d['login_token'],
					'id' => $tokenData['account']['id'],
					'previleges' => $tokenData['account']['previleges']
				];
			}
			else if( $META->require(...$requirements['read']) && AuthMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$tokenData = $CONFIG->decrypt($META->auth['login_token']);

				$additionalParam =
				[
					'crud_method' => $META->crud_method,
					'login_token' => $d['login_token'],
					'id' => $tokenData['account']['id'],
					'previleges' => $tokenData['account']['previleges']
				];
			}
			else
			{
				throw new \Exception("insufficient meta", 1);
			}

			$this->_config = $CONFIG;
			$this->_prop = array_merge($this->_prop, $additionalParam);
		}

		/**
		 *	...
		 *
		 * @access public
		 * @static
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public static function createInstance( IConfig $CONFIG, AuthMeta $META )
		{
			try
			{
				return new self($CONFIG, $META);
			}
			catch( \Exception $EXCEPTION )
			{
				return null;
			}
		}

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getId() : ?string
	    {
	    	return $this->_prop['id'] ?? null;
	    }

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getPrevileges() : Array
	    {
	    	$this->_requirePrevilegeSegment();

	    	return $this->_prop['previleges'];
	    }

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getStatus() : string
	    {
			$this->_requireBasicSegment();

	    	return $this->_prop['status'];
	    }

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function isEnabled() : bool
	    {
	    	$this->_requireBasicSegment();

	    	return (string)AuthStatusMeta::ACTIVE === (string)$this->getStatus();
	    }

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
		public function isLoggedIn( Array $FILTER = null ) : bool
		{
			return isset( $this->_prop['login_token'] );
		}

		public function hasPrevilege( string $PREVILEGE )
		{
			$db 	= $this->_config->getDbAdapter();
			$params = [ 'userid' => self::getSessionId(), 'previlege' => $PREVILEGE ];

			$db->query
			("
				SELECT A.uid FROM `auth` A 
				INNER JOIN `account_previleges` AP ON A.uid = AP.auth_fk
				WHERE A.uid = :userid AND AP.previlege_fk = :previlege",

				$params
			);

			return !!count($db->fetch());
		}

		/**
		 * Checks if a user is logged in with certain previlege/s
		 *
		 * @access protected
		 * @return void
		 * @static
		 * @final
		 * @since Method available since Beta 1.0.0
		 */
		public function isLoggedAs( string ...$PREVILEGES ) : bool
		{
			$count = 0;
			$params = [ 'auth_id' => self::sessionInfo($this->_config)->id ];
			$inQryFrag = [];

			foreach($PREVILEGES as $previlege)
			{
				$countIndex = "p" . $count;
				$params[$countIndex] = $previlege;
				$inQryFrag[] = ":{$countIndex}";
				$count++;
			}

			$db = $this->_config->database;
			$inQry = 1;

			if( count($inQryFrag) )
			{
				$inQry = " P.previlege_fk IN(" . implode(", ", $inQryFrag) . ")";
			}

			$db->query
			("
				SELECT `uid` FROM `auth` A
				INNER JOIN `account_previleges` P ON A.uid = P.auth_fk
				WHERE A.uid = :auth_id AND {$inQry}",

				$params
			);

			$foundRows = $db->query_num_rows();

			return $foundRows && $foundRows === count($PREVILEGES);
		}
		
		/**
		 * This will initialize the use of session.
		 *
		 * @access protected
		 * @return void
		 * @static
		 * @final
		 * @since Method available since Beta 1.0.0
		 */

		final protected static function requireSession()
		{
			/* 
			 * only initialize the session if not already set. this is to guard against initializing the session
			 * again which will produce an error.
			 * then cache the session value for this account only once.
			 */
			if( false === isset($_SESSION) )
			{
				session_start();
			}

			if( true === isset($_SESSION['account']) && null === self::getSessionId() )
			{
				self::$_session = $_SESSION['account'];
			}
		}
		
		/**
		 * @access public
		 * @static
		 * @final
		 * @throws
		 * @return 	- string 	A current user's session.
		 * 			- null  	No session is set.
		 * @since Method available since Beta 1.0.0
		 */
		final public static function getSessionId()
		{
			return self::$_session;	
		}

		/**
		 * @access public
		 * @static
		 * @final
		 * @throws
		 * @return 	...
		 * @since Method available since Beta 1.0.0
		 */
		final public static function sessionInfo( IConfig $CONFIG ) : \stdClass
		{
			$data = new \stdClass;

			$data->id = self::$_session;

			if( $data->id )
			{
				$db = $CONFIG->database;
				$param =
				[
					'auth_id' => $data->id
				];

				$db->query("SELECT `previlege_fk` FROM `account_previleges` WHERE `auth_fk` = :auth_id", $param);

				if( $db->query_num_rows() > 0 )
				{
					foreach( $db->fetch() as $row )
					{
						$data->previleges[] = $row['previlege_fk'];
					}
				}

				$infoParam = [ 'uid' => $data->id ];

				$db->query
				("
					SELECT A.enabled, A_BA.status_fk AS `status`
					FROM `auth` A
					INNER JOIN `account_basic_information` A_BA ON A.uid = A_BA.auth_fk
					WHERE A.uid = :uid
					LIMIT 0, 1",

					$infoParam
				);

				$mapSessionInfoFn = function($row) use (&$data)
				{
					$data->status = $row['status'];
				};

				$db->fetch($mapSessionInfoFn);
			}

			return $data;
		}

		/**
		 *	@access public
		 *	@return - boolean - return true if session is set otherwise false.
		 *
		 *	@since Method available since Beta 1.0.0
		 */
		public function logout()
		{
			unset($_SESSION['account']);

			return !$this->isLoggedIn();
		}
		
		/**
		 * account authentication method.
		 *
		 * @param ... 	...		...
		 * @param ... 	...		...
		 * @return ...
		 * @access public
		 * @since Method available since Beta 1.0.0
		 */
		public function login() : ?String
		{
			if( $this->isLoggedIn() )
			{
				return $this->_prop['login_token'];
			}

			$iConfig  = $this->_config;
			$username = $this->_prop['username'];
			$password = $this->_prop['password'];

			$authMeta =
			[
				'auth' =>
				[
					'crud_method' => $this->_prop['crud_method'],
					'username' => $username,
					'password' => $password,
					'previleges' => $this->getPrevileges()
				]
			];

			$authMeta = AuthMeta::searchAndRemove($authMeta, null);
			$iAuthMeta = AuthMeta::createInstance($iConfig, $authMeta);

			if( $iAuthMeta instanceof AuthMeta && !$iAuthMeta->require(["auth", "crud_method", "username", "password"]) )
			{
				return NULL;
			}

			$params = [ 'username' => $username, 'password' => md5($password) ];

			/*
			 * build previlege sql query
			 * set the previlegeCount variable for check later. the default it 1.
			 */
			$previlegeQryFrag = "";
			$previleges = $this->getPrevileges();

			if( count($previleges) )
			{
				$previlegesBindings = [];

				foreach( $previleges as $key => $previlege )
				{
					$previlegeParam = "previlege{$key}";
					$params[$previlegeParam] = $previlege;
					$previlegesBindings[] = ":{$previlegeParam}";
				}

				$previlegeQryFrag = " AND A_P.previlege_fk IN (" . implode(", ", $previlegesBindings) . ") ";
			}

			$db = $iConfig->getDbAdapter();

			$db->query
			("
				SELECT A.uid FROM `auth` A 
				LEFT JOIN `auth_previleges` A_P ON A.uid = A_P.auth_fk AND A_P.enabled = 1
				WHERE A.username = :username AND A.password = :password
				{$previlegeQryFrag}",

				$params
			);

			$rowCount = $db->query_num_rows();

			if( $rowCount && ( $previleges ?$rowCount === count($previleges) :true ) )
			{
				$payload =
				[
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + 60,
					'account' =>
					[
						'id' => $db->fetch()[0]['uid'],
						'previleges' => $this->getPrevileges()
					]
				];

				$encryptedString = $iConfig::encrypt($payload);

				return !!$encryptedString ?$encryptedString :NULL;
			}

			return NULL;
		}





        /**
         *  ...
         *
         * @access private
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        private function _requireBasicSegment()
        {
            if( !$this->_propertySegment['basic'] && $this->getId() )
            {
                $d = self::t_AuthOps_getInfoById($this->_config, $this->getId());

                $this->_prop =
                [
                	'status' => $d['status']

                ] + $this->_prop;

                $this->_propertySegment['basic'] = true;
            }
        }





        /**
         *  ...
         *
         * @access private
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        private function _requirePrevilegeSegment()
        {
            if( !$this->_propertySegment['previleges'] && $this->getId() )
            {
                $p = self::t_AuthOps_getPrevilegesById($this->_config, $this->getId());

                $this->_prop =
                [
                	'previleges' => array_map( fn($data) => $data['previlege'] , $p )

                ] + $this->_prop;

                $this->_propertySegment['previleges'] = true;
            }
        }





		/**
		 *	...
		 *
		 * @access public
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function create() :? String
		{
			$iConfig = $this->_config;
			$db = $iConfig->getDbAdapter();
			$singleTransaction = $db->beginTransaction();

			$authMeta =
			[
				'auth' =>
				[
					'crud_method' => $this->_prop['crud_method'],
					'username' => $this->_prop['username'],
					'password' => $this->_prop['password'],
					'previleges' => $this->getPrevileges(),
					'status' => $this->getStatus(),
					'disabled' => !$this->isEnabled()
				]
			];

			$iAuthMeta = AuthMeta::createInstance($iConfig, $authMeta);
			
			if( false === is_a($iAuthMeta, AuthMeta::t_UtilOps_classWithBackslash()) )
			{
				return NULL;
			}

			$db->query
			("
				INSERT INTO `auth`(`username`, `password`, `status_fk`, `disabled`)
				VALUES(:username, :password, :status_fk, :disabled)",

				[
					'username' 	=> $this->_prop['username'],
					'password' 	=> md5($this->_prop['password']),
					'status_fk' => $this->getStatus(),
					'disabled' 	=> (!$this->isEnabled()) ?1 :0
				]
			);

			$authId = $db->lastInsertId();

			$previleges = $this->getPrevileges();

			if( !empty($previleges) )
			{
				$previlegeParam = [];
				$previlegeValuesQryFrags = [];

				foreach( $previleges as $key => $previlege )
				{
					$authKey = "auth" . $key;
					$previlegeKey = "previlege" . $key;
					$statusKey = "status" . $key;

					$previlegeParam[$authKey] = $authId;
					$previlegeParam[$previlegeKey] = $previlege;
					$previlegeParam[$statusKey] = 1;
					$previlegeValuesQryFrags[] = ":" . $authKey . ", :" . $previlegeKey . ", :" . $statusKey;
				}

				$db->query
				("
					INSERT INTO `auth_previleges`(`auth_fk`, `previlege_fk`, `enabled`) VALUES( " . implode("), (", $previlegeValuesQryFrags) . " )",

					$previlegeParam
				);
			}

			$singleTransaction && $db->commit();

			return $this->_prop['id'] = $authId;
		}





		/**
		 *	...
		 *
		 * @access public
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function update( AuthMeta $META ) : ?bool
		{
			# requiring crud_method and is essential in validating other properties

			$updateRequirements = [ "crud_method", [ "auth", "id" ] ];

			# crud_method must be update
			# id must be equals the value during the class construction

			if( $META->require(...$updateRequirements) && $META->crud_method === AuthMeta::CRUD_METHOD_UPDATE )
			{
				$auth = $META->auth;

				if( $this->getId() !== ( $id = $auth['id'] ?? null ) )
				{
					return false;
				}
			}
			else
			{
				return false;
			};

			# certain properties can only be made changeable
			#
			# synopsis: [ database table column name => property name ]

	    	$allowedProperties['auth'] =
	    	[
	    		[ "password", "password" ]
	    	];

	    	$param = $fieldSetQry = [];

	    	foreach($allowedProperties as $segment => $props)
	    	{
	    		if( !($segmentProp = $META->$segment) )
	    		{
	    			continue;
	    		}

	    		foreach( $props as [$column, $name] )
	    		{
	    			$value = $segmentProp[$name];

	    			if( "auth" === $segment && "password" === $column )
	    			{
	    				$value = md5($value);
	    			}

	    			if( array_key_exists($name, $segmentProp) )
	    			{
	    				$param[$segment][$name] = $value;
	    				$fieldSetQry[$segment][] = "`{$column}` = :{$name}";
	    			}
	    		}
	    	}

	    	# early exit on empty properties

	    	if( !$param )
	    	{
	    		return null;
	    	}

	    	# facilitate the changes

	    	$iDb = $this->_config->getDbAdapter();
	    	$dbTransaction = $iDb->beginTransaction();

	    	if( $param['auth'] ?? null )
	    	{
	    		$param['auth']['uid'] = $this->getId();
	    		$authSetQry = implode(", ", $fieldSetQry['auth']);

				$iDb->query
				("
					UPDATE `auth` SET {$authSetQry}
					WHERE `uid` = :uid",

					$param["auth"]
				);

				$this->_propertySegment['basic'] = false;
	    	}

	    	$dbTransaction && $iDb->commit();

	    	return true;
		}
	}

?>