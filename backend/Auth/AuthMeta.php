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
	use App\Auth\AuthPrevilegeMeta;

	Class AuthMeta extends \App\AppMeta
	{
		use \App\Auth\TAuthOps,
			\App\Auth\TAuthStatusOps,
			\App\Office\TOfficeOps;

	    /**
	     * ...
	     *
	     * @var string
	     * @access public
	     */
	    const CRUD_METHOD_CREATE = "create";
	    const CRUD_METHOD_READ   = "read";
	    const CRUD_METHOD_UPDATE = "update";





		/**
		 * This is the web application's system configuration settings.
		 * 
		 * @var App\IConfig
		 * @access private
		 */
		private IConfig $_config;





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
		public function __construct( IConfig $CONFIG )
		{
			$this->_config = $CONFIG;

			$this->_metadata =
			[
				'auth' =>
				[
					'crud_method' =>
					[
						'type' => "enum",
						'collection' => [ self::CRUD_METHOD_CREATE, self::CRUD_METHOD_READ, self::CRUD_METHOD_UPDATE ],
						'null-allowed' => false
					],
					'username' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 32,
						'null-allowed' => false
					],
					'password' =>
					[
						'type' => "string",
						'length-min' => 4,
						'length-max' => 32,
						'null-allowed' => false
					],
					're_password' =>
					[
						'type' => "string",
						'length-min' => 4,
						'length-max' => 16,
						'null-allowed' => false
					],
					'current_password' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'previleges' =>
					[
						'type' => "string",
						'null-allowed' => true,
						'is-array' => true,
					],
					'status' =>
					[
						'type' => "string",
						'null-allowed' => false,
					],
					'login_token' =>
					[
						'type' => "string",
						'null_allowed' => false
					]
				],

				/*'update' =>
				[
					'admin_auth' => [ 'type' => Auth::t_UtilOps_classWithBackslash(), 'nullable' => false ],
					'account' =>[ 'type' => "string", 'nullable' => false ],
					'status' => [ 'type' => "string", "length-min" => 1, "length-max" => 255, 'nullable' => false ],
					'enabled' => [ 'type' => "boolean", 'nullable' => false ],

					'verification_domain' => [ 'type' => "string", 'nullable' => false ],
					'verification_code' => [ 'type' => "string", 'nullable' => false, 'alias' => "verification code" ],

					'user_auth' => [ 'type' => Auth::t_UtilOps_classWithBackslash(), 'nullable' => false ],
					'current_password',
					'password' => [ 'type' => "string", "length-min" => 4, "length-max" => 16, 'nullable' => false ],
					're_password' => [ 'type' => "string", "length-min" => 4, "length-max" => 16, 'nullable' => false ]
				]*/
			];
		}

		/**
		 *	...
		 *
		 *	@access public
		 *	@param ...
		 *	@return ...
		 *	@since Method available since Beta 1.0.0
		 *
		 *	...
		 *	...
		 *	...
		 *	...
		 */
		protected function _setSpecialProperty( $SETTINGS )
		{
			$iConfig = $this->_config;
			$db = $iConfig->getDbAdapter();
			$name = $SETTINGS->getName();
			$field = $SETTINGS->getField();
			$alias = $SETTINGS->getAlias() ?? $name;
			$newValue = $SETTINGS->getNewValue();

			switch( $field )
			{
				case 'update':
					switch($SUBFIELD)
					{
						case 'user_auth':
							if( false === $VALUE->hasPrevilege(self::BROKER) )
							{
								$this->error = "{$alias}is invalid";
							}
						break;

						case 'current_password':
							$auth = @$VALUE_GROUP['user_auth'];

							if( array_key_exists("user_auth", $VALUE_GROUP) && $VALUE_GROUP['user_auth']->isLoggedIn() )
							{
								$oldPassword = $this->t_AuthOps_getPasswordById($config, $auth->sessionInfo($config)->id);

								if( false === $oldPassword || md5($VALUE) !== $oldPassword )
								{
									$this->error = "incorrect old password";
								}
							}
						break;

						case 're_password':
							if( $VALUE !== @$VALUE_GROUP['password'] )
							{
								$this->error = "password do not match";
							}
						break;

						case 'admin_auth':
							if( false === $VALUE->hasPrevilege(self::ADMIN) )
							{
								$this->error = "{$alias}is invalid";
							}
						break;

						case 'account':
							if( false === $this->t_AccountOps_exists($config, $VALUE) )
							{
								$this->error = "account does not exist";
							}
						break;

						case 'status':
							if( true === $this->t_AccountStatusOps_exists($config, $VALUE) )
							{
								/*
								 * account activation
								 */
								if( array_key_exists("account", $VALUE_GROUP) )
								{
									if( false === in_array($VALUE, [1], false) )
									{
										$this->error = "{$alias}is restricted";
									}
									else
									{
										$accountInfo = self::t_AccountOps_getInfoById($config, $VALUE_GROUP['account']);

										if( 3 != $accountInfo['status'] )
										{
											$this->error = "cannot update status from {$accountInfo['status']}";
										}
									}
								}
								else
								{
									$this->error = "cannot validate status, account not found";
								}
							}
							else
							{
								$this->error = "{$alias}is invalid";
							}
						break;

						case "verification_domain":
							if( false === in_array($VALUE, AppVerificationMeta::codeList(), false) )
							{
								$this->error = "{$alias} is unknown";
							}
						break;

						case "verification_code":
							if( isset($VALUE_GROUP['verification_domain'], $VALUE_GROUP['account']) )
							{
								$codeRecord = self::t_AppVerificationCodeOps_getByDomain($config, $VALUE_GROUP['account'], $VALUE_GROUP['verification_domain']);

								if( $codeRecord )
								{
									if( $VALUE !== $codeRecord )
									{
										$this->error = "{$alias}is invalid";
									}
								}
								else
								{
									$this->error = "account has no existing code record";
								}
							}
							else
							{
								$this->error = "cannot validate, verification_domain and account is required";
							}
						break;
					}
				break;

				case 'auth':
					switch( $name )
					{
						case 'id':
							$callback = function( $settings, $loginToken ) use ($iConfig, $alias)
							{
								if( $data = $this->_config::decrypt( $loginToken ) )
								{
									$memberId = $data['account']['id'];

									if( false == $this->t_OfficeOps_isMember($iConfig, $settings->getCurrentValue(), $memberId ) )
									{
										return "{$alias} dont exist";
									}
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, ["auth", "login_token"]);
						break;

						case 'username':
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								switch( $crudMethod )
								{
									case AuthMeta::CRUD_METHOD_READ:
										// noop
									break;

									case AuthMeta::CRUD_METHOD_CREATE:
									default:
										if( true ===  $this->t_AuthOps_usernameExists($iConfig, $settings->getNewValue()) )
										{
											return "{$alias} is already taken";
										}
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method");
						break;

						case "re_password":
							$callback = function( $settings, $password ) use ($iConfig, $alias)
							{
								if( $password !== $settings->getNewValue() )
								{
									return "{$alias} does not match";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, ["auth", "password"]);
						break;

						case 'current_password':
							$callback = function() use ($SETTINGS, $iConfig, $alias)
							{
								$loginToken = $SETTINGS->spawn("login_token", null, "auth");

								try
								{
									$data = $iConfig->decrypt($loginToken->getCurrentValue());

								if( $this->t_AuthOps_isCurrentPassword($iConfig, $data['account']['id'], $SETTINGS->getNewValue()) )
									{
										return false;
									}
								}
								catch( \Exception $e )
								{
									return
									[
										'metadata' => $SETTINGS,
										'message' => "decrypt failed"
									];
								}

								return
								[
									'metadata' => $SETTINGS,
									'message' => "{alias} is invalid"
								];
							};

							$this->_errorDependencyHandler(["auth", "login_token"], $callback);

							$callback = function() use ($SETTINGS, $iConfig, $alias)
							{
								$id = $SETTINGS->spawn("id", null, "auth");

								if( $id && !empty($id->getCurrentValue()) )
								{
									if( $this->t_AuthOps_isCurrentPassword($iConfig, $id->getCurrentValue(), $SETTINGS->getNewValue()) )
									{
										return false;
									}

									return
									[
										'metadata' => $SETTINGS,
										'message' => "{$alias} is invalid"
									];
								}
							};

							$this->_errorDependencyHandler(["auth", "id"], $callback);
						break;

						case 'previleges':
							/*$stash =
							[
								AuthPrevilegeMeta::LEAD_BUYER, AuthPrevilegeMeta::MERCHANT, AuthPrevilegeMeta::ADMIN,
								AuthPrevilegeMeta::STAFF, AuthPrevilegeMeta::OFFICE_OWNER
							];

							if( !in_array($SETTINGS->getNewValue(), $stash, false) )
							{
								$this->setLastError("{$alias} is invalid");
							}
							else if( false === $this::t_AuthOps_previlegeExists($iConfig, $SETTINGS->getNewValue()) )
							{
								$this->setLastError("{$alias} is not available");
							}*/
						break;

						case "status":
							if( !$this->t_AuthStatusOps_exists($iConfig, $SETTINGS->getNewValue()) )
							{
								$this->setLastError("{$alias} is invalid");
							}
						break;

						case "login_token":
							if( !is_array($this->_config::decrypt($SETTINGS->getNewValue())) )
							{
								$this->setLastError("{$alias} is invalid");
							}
						break;
					}
				break;

				default:
					# code...
					break;
			}
		}
	}

?>