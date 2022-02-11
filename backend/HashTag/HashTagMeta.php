<?php

	/**
	 * ...
	 *
	 * @category	App\HashTag
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\HashTag;

	use App\IConfig;





	Class HashTagMeta extends \App\AppMeta
	{
		use \App\HashTag\THashTagOps;

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
				'hashtag' =>
				[
					'id' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'name' =>
					[
						'type' => "string",
						'length-max' => 64,
						'null-allowed' => false,
					],
					'created_by' =>
					[
						'type' => "\App\Account\Account",
						'null-allowed' => false
					],
					'date_created' =>
					[
						'type' => "int",
						'zero-allowed' => false,
						'decimal-allowed' => false,
						'null-allowed' => false,
						'unsigned' => true
					],
					'disabled' =>
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
				case 'hashtag':
					switch( $name )
					{
						case "name":
							if( !$this->t_HashTagOps_isAvailable($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not available");
							}
						break;

						case "created_by":
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