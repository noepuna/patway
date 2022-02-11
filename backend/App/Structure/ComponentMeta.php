<?php

	/**
	 * ...
	 *
	 * @category	App\Structure
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Structure;

	use App\IConfig;





	Class ComponentMeta extends \App\AppMeta
	{
		use \Core\Util\TUtilOps,
			\App\Structure\TAppComponentOps;

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

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var App\IConfig;
	     * @access private
	     */
	    private const _metadata =
	    [
			'id' =>
			[
				'id' => "int",
				'null-allowed' => false,
			],
			'name' =>
			[
				'type' => "string",
				'length-max' => 32,
				'null-allowed' => false
			],
			'structure' =>
			[
				'type' => "\App\Structure\Structure",
				'null-allowed' => false
			],
			'enabled' =>
			[
				'type' => "boolean",
				'null-allowed' => false
			]
	    ];





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
				'app_component' => self::_metadata
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
				case "app_component":
					switch( $name )
					{
						case "id":
							if( !$this->t_appComponentOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} is unsupported");
							}
						break;

						case "structure":
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