<?php

	/**
	 * ...
	 *
	 * @category	App\BehaviorBaseSafety
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\BehaviorBaseSafety;

	use App\IConfig;
	use App\Account\Task\TaskMeta;





	Class ObservationMeta extends \App\AppMeta
	{
		use \App\BehaviorBaseSafety\TBBSObservationOps,
			\App\BehaviorBaseSafety\TBBSObservationTypeOps;

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
				'bbs_observation' =>
				[
					'id' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'types' =>
					[
						'type' => "string",
						'null-allowed' => false,
						'is-array' => true
					],
					'properties' =>
					[
						'type' => "\App\BehaviorBaseSafety\Property\Property",
						'is-array' => true,
						'null-allowed' => false
					],
					'observer' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'supervisor' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'notes' =>
					[
						'type' => "string",
						'length-max' => 225,
					],
					'recommendation' =>
					[
						'type' => "string",
						'length-max' => 225,
					],
					'action_taken' =>
					[
						'type' => "string",
						'length-max' => 225,
					],
					'feedback_to_coworkers' =>
					[
						'type' => "string",
						'length-max' => 225,
					],
			    	'attachment_file' =>
			    	[
						'type' => "\App\File\File",
						'null-allowed' => true
			    	],
					'created_by' =>
					[
						'type' => "\App\Account\Account",
						'null-allowed' => false
					],
					'co_worker' =>
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
				case 'bbs_observation':
					switch( $name )
					{
						case "id":
							if( !$this->t_BBSObservationOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$callback = function($settings, $crudMethod, $created_by) use ($iConfig, $alias)
							{
								$observationId = $settings->getNewValue();

								switch( $crudMethod )
								{
									case ObservationMeta::CRUD_METHOD_READ:
										if( !$this->t_BBSObservationOps_isAvailable($iConfig, $observationId, $created_by->getId(), false) )
										{
											return "{$alias} not available";
										}
									break;
									case ObservationMeta::CRUD_METHOD_UPDATE:
										if( !$this->t_BBSObservationOps_isAvailable($iConfig, $observationId, $created_by->getId()) )
										{
											return "{$alias} not available";
										}
									break;
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method", ["bbs_observation", "created_by"]);

							$coWorkerCallback = function($settings, $coWorker) use ($iConfig, $alias)
							{
								$observationId = $settings->getNewValue();

								if( !$this->t_BBSObservationOps_isAvailable($iConfig, $observationId, $coWorker->getId(), false) )
								{
									return "{$alias} not available";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $coWorkerCallback, ["bbs_observation", "co_worker"]);
						break;

						case "types":
							if( !$this->t_BBSObservationTypeOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} is unsupported");
							}
							else if( !$this->t_BBSObservationTypeOps_isAvailable($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} is not available");
							}
						break;

						case "attachment_file":
							//
							// the right appcomponent must be provided
							// and must be enabled too
							//
							$iComponent = $newValue->getAppComponent();

							if( "4" !== $iComponent->getId() || !$iComponent->isEnabled() )
							{
								$this->setLastError("{$alias} is invalid");
							}
							//
							// must be crud create
							//
							else if( $newValue->getId() )
							{
								$this->setLastError("{$alias} is invalid");
							}
							//
							// must be a pdf file
							//
							else if( !preg_match("/\.pdf$/i",$newValue->getUrlPath(), $output_array) )
							{
								$this->setLastError("{$alias} must be a pdf file");
							}
						break;

						case "created_by":
						case "co_worker":
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								$iAccount = $settings->getNewValue();

								if( $iAccount->taskAvailable( TaskMeta::BBS_OBSERVATION_A ) )
								{
									return false;
								}
								else
								{
									switch( $crudMethod )
									{
										case self::CRUD_METHOD_CREATE:
											if( false == $iAccount->taskAvailable( TaskMeta::BBS_OBSERVATION_C ) )
											{
												return "{$alias} has no create access";
											}
										break;

										case self::CRUD_METHOD_READ:
											if( false == $iAccount->taskAvailable( TaskMeta::BBS_OBSERVATION_R ) )
											{
												return "{$alias} has no read access";
											}
										break;

										case self::CRUD_METHOD_UPDATE:
											if( false == $iAccount->taskAvailable( TaskMeta::BBS_OBSERVATION_U ) )
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