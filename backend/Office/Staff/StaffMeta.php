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
	use App\Auth\Auth;
	use App\Auth\AuthPrevilegeMeta;
	use App\Account\Task\TaskMeta;





	Class StaffMeta extends \App\Account\AccountMeta
	{
		use \App\Office\Staff\TStaffOps;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Resource\IConfig;
	     * @access private
	     */
		private IConfig $_config;





		public function __construct( IConfig $CONFIG )
		{
			Parent::__construct($CONFIG);

			$this->_config = $CONFIG;

			$this->_metadata =
			[
				'staff' =>
				[
					'admin' =>
					[
						'type' => "App\Office\Admin\Admin",
						'null-allowed' => false
					],
					'department' =>
					[
						'type' => "App\Office\Staff\DepartmentEntry",
						'is-array' => true,
						'null-allowed' => false
					]
				]

			] + $this->_metadata;
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
			Parent::_setSpecialProperty($SETTINGS);

			$iConfig = $this->_config;
			$db = $iConfig->getDbAdapter();
			$name = $SETTINGS->getName();
			$field = $SETTINGS->getField();
			$alias = $SETTINGS->getAlias() ?? $name;
			$newValue = $SETTINGS->getNewValue();

			switch( $field )
			{
				case "account":
					switch( $name )
					{
						case "auth":
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								$iAuth = $settings->getNewValue();
								$previleges = $iAuth->getPrevileges();
								$error = false;

								if( false === in_array(AuthPrevilegeMeta::STAFF, $previleges) )
								{
									$error = "{$alias} is invalid";
								}

								if( $this::CRUD_METHOD_READ === $crudMethod && !$error )
								{
									if( !$this->t_staffOps_isAvailable($iConfig, $iAuth->getId()) )
									{
										$error = "{$alias} is not available";
									}
								}

								return $error;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method");
						break;
					}
				break;

				case "staff":
					switch( $name )
					{
						case "admin":
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								if( $this::CRUD_METHOD_CREATE === $crudMethod && !$settings->getNewValue()->getId() )
								{
									return "{$alias} is not authenticated";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method");
						break;

						case "department":
							$callback = function( $settings, $admin ) use ($iConfig, $alias)
							{
								if( $admin->getId() != $settings->getNewValue()->getId() )
								{
									return "{$alias} is not authorized";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, [ "staff", "admin" ]);
						break;
					}
				break;

				case null:
					switch( $name )
					{
						case "task":
							$allowedTasks =
							[
								TaskMeta::EVENT_A,
								TaskMeta::BBS_OBSERVATION_A,
								TaskMeta::CRISIS_MANAGEMENT_A
							];

							if( !in_array($newValue->getId(), $allowedTasks) )
							{
								$this->setLastError("{$alias} is not allowed");
							}
						break;
					}
				break;
			}
		}
	}

?>