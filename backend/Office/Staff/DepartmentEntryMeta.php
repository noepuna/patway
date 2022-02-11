<?php

	/**
	 * All Business Logic for a Department Entry
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





	Class DepartmentEntryMeta extends \App\AppMeta
	{
		use \Core\Util\TUtilOps;

		/**
		 * ...
		 *
	     * @var string
	     * @access public
		 */
		const CRUD_METHOD_CREATE = "create";
		const CRUD_METHOD_READ 	 = "read";
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

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array
	     * @access private
	     */
	   	private Array $_prop = [];





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
		public function __construct( \App\IConfig $CONFIG )
		{
			$this->_config = $CONFIG;

			$this->_metadata =
			[
				'department_entry' =>
				[
					'department' =>
					[
						'type' => "App\Office\Department\Department",
						'null-allowed' => false
					],
					'deleted' =>
					[
						'type' => "boolean",
						'null-allowed' => false
					]
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
				case "department_entry":
					switch( $name )
					{
						case "department":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} is invalid");
							}
						break;
					}
				break;
			}
		}
	}

?>