<?php

	/**
	 * ...
	 *
	 * @category	App\Event
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Event;

	use App\IConfig;
	use App\Account\Task\TaskMeta;





	Class EventMeta extends \App\AppMeta
	{
		use \App\Event\TEventOps;

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
				'event' =>
				[
					'id' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'title' =>
					[
						'type' => "string",
						'length-max' => 64,
						'null-allowed' => false
					],
					'description' =>
					[
						'type' => "string",
						'length-max' => 225,
						'null-allowed' => true
					],
					'closed' =>
					[
						'type' => "boolean",
						'null-allowed' => false
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
				case 'event':
					switch( $name )
					{
						case "id":
							if( !$this->t_eventOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$callback = function($settings, $crudMethod, $created_by) use ($iConfig, $alias)
							{
								$eventId = $settings->getNewValue();

								switch( $crudMethod )
								{
									case EventMeta::CRUD_METHOD_READ:
										if( !$this->t_eventOps_isAvailable($iConfig, $eventId, $created_by->getId()) )
										{
											return "{$alias} not available";
										}
									break;
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method", ["event", "created_by"]);
						break;

						case "created_by":
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								$iAccount = $settings->getNewValue();

								if( $iAccount->taskAvailable( TaskMeta::EVENT_A ) )
								{
									return false;
								}
								else
								{
									switch( $crudMethod )
									{
										case EventMeta::CRUD_METHOD_CREATE:
											if( false == $iAccount->taskAvailable( TaskMeta::EVENT_C ) )
											{
												return "{$alias} has no create access";
											}
										break;

										case EventMeta::CRUD_METHOD_READ:
											if( false == $iAccount->taskAvailable( TaskMeta::EVENT_R ) )
											{
												return "{$alias} has no read access";
											}
										break;

										case EventMeta::CRUD_METHOD_UPDATE:
											if( false == $iAccount->taskAvailable( TaskMeta::EVENT_U ) )
											{
												return "{$alias} has no update access";
											}
										break;
									}
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