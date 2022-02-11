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
	use App\Account\Task\TaskMeta;





	Class OwnerMeta extends \App\Account\Member\MemberMeta
	{
		use \App\Office\TOwnerOps;

	    /**
	     * ...
	     *
	     * @var Array
	     * @access private
	     */
		const ACCESSIBLE_TASKS =
		[
		    /*TaskMeta::OFFICE_ACCOUNT_C,
		    TaskMeta::OFFICE_ACCOUNT_R,
		    TaskMeta::OFFICE_ACCOUNT_U,
		    TaskMeta::OFFICE_ACCOUNT_D,
		    TaskMeta::OFFICE_ACCOUNT_A,
		    TaskMeta::LEAD_C,
		    TaskMeta::LEAD_R,
		    TaskMeta::LEAD_U,
		    TaskMeta::LEAD_D,
		    TaskMeta::LEAD_A,
		    TaskMeta::OFFICE_TASK_C,
		    TaskMeta::OFFICE_TASK_R,
		    TaskMeta::OFFICE_TASK_U,
		    TaskMeta::OFFICE_TASK_D,
		    TaskMeta::OFFICE_TASK_A*/
		];

	    /**
	     * ...
	     *
	     * @var string
	     * @access public
	     */
	    const ENABLED = 1;
	    const DISABLED = 0;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Resource\IConfig;
	     * @access private
	     */
		private $_config;





		public function __construct( IConfig $CONFIG )
		{
			Parent::__construct($CONFIG);

			$this->_config = $CONFIG;

			$this->_metadata =
			[
				'office' =>
				[
					'name' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
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
				case 'account':
					switch( $name )
					{
						case 'auth':
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								$iAuth = $settings->getNewValue();
								$previleges = $iAuth->getPrevileges();
								$error = false;

								if( false === in_array(AuthPrevilegeMeta::OFFICE_OWNER, $previleges) )
								{
									$error = "{$alias} is invalid";
								}

								if( $this::CRUD_METHOD_READ === $crudMethod && !$error )
								{
									if( !$this->t_OwnerOps_getInfoById($iConfig, $iAuth->getId()) )
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

				case "office":
					switch( $name )
					{
						case "name":
							$callback = function($settings, $crudMethod) use ($alias, $iConfig)
							{
								if( OwnerMeta::CRUD_METHOD_CREATE === $crudMethod && $this->t_ownerOps_nameExists($iConfig, $settings->getNewValue()) )
								{
									return "{$alias} is not available";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method");
						break;
					}
				break;
			}
		}
	}

?>