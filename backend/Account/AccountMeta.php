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
	use App\Auth\Auth;
	use App\Account\AccountAddressMeta;
	use App\Account\AccountContactMeta;

	Class AccountMeta extends \App\AppMeta
	{
		use \Core\Util\TUtilEmailOps,
			\Core\Util\TUtilAddressOps,
			\Core\Util\TUtilPhonenumberOps,
			\App\Account\TAccountOps,
			\App\TAppContactOps,
			\App\TAppAddressOps,
			\App\Account\Task\TTaskOps;

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
	     * ...
	     *
	     * ...
	     *
	     * @var App\IConfig;
	     * @access private
	     */
	    private IConfig $_config;

		public function __construct( \App\IConfig $CONFIG )
		{
			$this->_config = $CONFIG;

			$this->_metadata =
			[
				'account' =>
				[
					'crud_method' =>
					[
						'type' => "enum",
						'collection' => [ self::CRUD_METHOD_CREATE, self::CRUD_METHOD_READ, self::CRUD_METHOD_UPDATE ],
						'null-allowed' => false
					],
					'auth' =>
					[
						'type' => Auth::t_UtilOps_classWithBackslash(),
						'null-allowed' => false,
					],
					'id' =>
					[
						'type' => "string",
						'zero-allowed' => false,
						'null-allowed' => false
					],
					'firstname' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => false
					],
					'lastname' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => false
					],
					'middlename' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => true
					],
					'email' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => false
					],
					'location_address' =>
					[
						'type' => "string",
						'length-max' => 255,
						'null-allowed' => false
					],
					'tel_num' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 20,
						'null-allowed' => true
					],
					'mobile_num' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => true
					]
				],
				'task' =>
				[
					'type' => "\App\Account\Task\Task",
					'null-allowed' => false,
					'is-array' => true
				]
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
				case 'account':
					switch( $name )
					{
						case "auth":
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								if( self::CRUD_METHOD_CREATE === $crudMethod && $settings->getNewValue()->getId() )
								{
									//return "{$alias} is invalid";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method");
						break;

						case 'id':
							if( !self::t_AccountOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} does not exist");
							}

							$callback = function($settings, $crudMethod) use ($iConfig, $alias)
							{
								if( !self::t_AccountOps_isAvailable($iConfig, $settings->getNewValue()) )
								{
									switch( $crudMethod )
									{
										case AccountMeta::CRUD_METHOD_CREATE:
										case AccountMeta::CRUD_METHOD_UPDATE:
											return "{$alias} is not available";
										break;

										case AccountMeta::CRUD_METHOD_READ:
											//noop
										break;
									}
								}

								return false;
							};

							#$this->_dependencyRegister( $SETTINGS, $callback, "crud_method" );
						break;

						case 'email':
							if( false === $this->t_UtilEmailOps_isValid($newValue) )
							{
								$this->setLastError("{$alias} is invalid");
							}
							else if( false === $this->t_AppContactOps_typeExists($iConfig, AccountContactMeta::TYPE_PRIMARY_EMAIL) )
							{
								$this->setLastError("{$alias} is unsupported");
							}
							else if( true === $this->t_AppContactOps_exists($iConfig, AccountContactMeta::TYPE_PRIMARY_EMAIL, $newValue) )
							{
								$this->setLastError("{$alias} is not available");
							}
						break;

						case 'location_address':
							if( false == $this->t_AppAddressOps_typeExists($iConfig, AccountAddressMeta::TYPE_LOCATION) )
							{
								$this->setLastError("{$alias} is unsupported");
							}
						break;

						case 'tel_num':
							//
						break;

						case 'mobile_num':
							//
						break;
					}
				break;
			}
		}
	}

?>