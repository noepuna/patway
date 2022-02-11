<?php

	/**
	 * ...
	 *
	 * @category	App\BehaviorBaseSafety\Property
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\BehaviorBaseSafety\Property;

	use App\IConfig;





	Class PropertyMeta extends \App\AppMeta
	{
		use \App\BehaviorBaseSafety\Property\TBBSObservationPropertyOps;

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
				'bbs_observation_property' =>
				[
					'observation' =>
					[
						'type' => "\App\BehaviorBaseSafety\Observation",
						'null-allowed' => false
					],
					'id' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'value' =>
					[
						'type' => "string",
						'null-allowed' => "mixed"
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
				case 'bbs_observation_property':
					switch( $name )
					{
						case "id":
							if( !$this->t_BBSObservationPropertyOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}
						break;

						case "observation":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} is invalid");
							}
						break;

						case "value":
							$idCallback = function( $settings, $id ) use ($iConfig, $alias)
							{
								$value = $settings->getNewValue();

								if( is_null($value) )
								{
									$propDetails = $this->t_BBSObservationPropertyOps_getDetails($iConfig, $id);

									if( 5 !== $propDetails['category'] )
									{
										return "{$alias} is required";
									}
								}

								return false;
							};

							$this->_dependencyRegister( $SETTINGS, $idCallback, ["bbs_observation_property", "id"] );
						break;
					}
				break;
			}
		}
	}

?>