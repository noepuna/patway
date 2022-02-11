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
	use App\Account\Account;
	use App\Office\Admin\IAdmin;
	use App\Office\Admin\AdminMeta;






	Class OfficeMeta extends \App\AppMeta
	{
		use \Core\Util\TUtilOps,
			\App\Office\TOfficeOps,
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
				'office' =>
				[
					'crud_method' =>
					[
						'type' => "enum",
						'collection' => [ self::CRUD_METHOD_CREATE, self::CRUD_METHOD_READ, self::CRUD_METHOD_UPDATE ],
						'null-allowed' => false
					],
					'account' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'creator' =>
					[
						'type' => Account::t_UtilOps_classWithBackslash(),
						'null-allowed' => false,
					],
					'task' =>
					[
						'type' => "\App\Account\Task\Task",
						'null-allowed' => false,
						'is-array' => true
					],
					'disabled' =>
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
				case 'office':
					switch( $name )
					{
						case "creator":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} is not logged in");
							}
						break;

						case "account":
							$callback = function( $settings, $creator ) use ($iConfig, $alias)
							{
								if( $this->t_OfficeOps_isMember($iConfig, $settings->getNewValue(), $creator->getId()) )
								{
									return false;
								}

								return "{$alias} not found";
							};

							$this->_dependencyRegister($SETTINGS, $callback, ["office", "creator"]);
						break;

						case 'task':
							$callback = function( $settings, $creator ) use ($iConfig, $alias)
							{
								$taskId = $settings->getNewValue()->getId();

								if( $creator instanceof IAdmin )
								{
									if( in_array($taskId, AdminMeta::ACCESSIBLE_TASKS) )
									{
										return false;
									}
								}

								if( 1 === 1 )
								{
									//check if this admin is an owner
								}

								if( 1 === 1 )
								{
									//check if this admin has this task available
								}

								return "{$alias} is unsupported";
							};

							$this->_dependencyRegister($SETTINGS, $callback, ["office", "creator"]);
						break;
					}
				break;
			}
		}
	}

?>