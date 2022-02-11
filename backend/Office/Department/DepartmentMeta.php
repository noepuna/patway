<?php

	/**
	 * ...
	 *
	 * @category	App\Office\Department
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Office\Department;

	use App\IConfig;





	Class DepartmentMeta extends \App\AppMeta
	{
		use \Core\Util\TUtilOps,
			\App\Office\Department\TOfficeDepartmentOps,
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
				'crud_method' =>
				[
					'type' => "enum",
					'collection' => [ self::CRUD_METHOD_CREATE, self::CRUD_METHOD_READ, self::CRUD_METHOD_UPDATE ],
					'null-allowed' => false
				],
				'office_department' =>
				[
					'id' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'name' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => false
					],
					'description' =>
					[
						'type' => "string",
						'length-max' => 256,
						'null-allowed' => true
					],
					'admin' =>
					[
						'type' => "\App\Office\Admin\Admin",
						'null-allowed' => false,
					],
					'enabled' =>
					[
						'type' => "boolean",
						'null-allowed' => false
					]
				],
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
				case "office_department":
					switch( $name )
					{
						case "id":
							if( !$this->t_OfficeDepartmentOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$callback = function($settings, $crudMethod, $admin) use ($alias, $iConfig)
							{
								switch( $crudMethod )
								{
									case self::CRUD_METHOD_READ:
										if( !$this->t_OfficeDepartmentOps_isAvailable($iConfig, $settings->getNewValue(), $admin->getId()) )
										{
											return "{$alias} is not available";
										}
									break;

									default:
										if( !$this->t_OfficeDepartmentOps_exists($iConfig, $settings->getNewValue(), $admin->getId()) )
										{
											return "{$alias} is not available";
										}
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method", ["office_department", "admin"]);
						break;

						case "name":
							$callback = function($settings, $admin) use ($alias, $iConfig)
							{
								$id = $settings->spawn("id", null, "office_department")->getCurrentValue();

								if( $this->t_officeDepartmentOps_nameExists($iConfig, $settings->getNewValue(), $admin->getId(), $id) )
								{
									return "{$alias} already exists";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, ["office_department", "admin"]);
						break;

						case "admin":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} not found");
							}
						break;
					}
				break;
			}
		}
	}

?>