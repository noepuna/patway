<?php

	/**
	 * ...
	 *
	 * @category	App\Construction\EnvironmentHealthSafety
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Construction\EnvironmentHealthSafety;

	use App\IConfig;
	use App\Account\Task\TaskMeta;





	Class EHSMeta extends \App\AppMeta
	{
		use \Core\Util\TUtilFileOps,
			\App\Construction\EnvironmentHealthSafety\TEHSOps;

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
			'ehs' =>
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
					'length-max' => 128,
					'null-allowed' => true
				],
		    	'icon_file' =>
		    	[
					'type' => "\App\File\File",
					'null-allowed' => false
		    	],
		    	'attachment_file' =>
		    	[
					'type' => "\App\File\File",
					'null-allowed' => false
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
				'enabled' =>
				[
					'type' => "boolean",
					'null-allowed' => false
				],
				'deleted' =>
				[
					'type' => "boolean",
					'null-allowed' => false
				]
		    ],
		    'settings' =>
			[
				'has_event' =>
				[
					'type' => "boolean",
					'null-allowed' => true
				]
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
				]

			] + self::_metadata;
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
				case 'ehs':
					switch( $name )
					{
						case "id":
							if( !$this->t_EHSOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$createdByCallback = function( $settings, $crudMethod, $created_by ) use ($iConfig, $alias)
							{
								$ehsId = $settings->getNewValue();

								switch( $crudMethod )
								{
									case EHSMeta::CRUD_METHOD_READ:
										if( !$this->t_EHSOps_isAvailable($iConfig, $ehsId, $created_by->getId()) )
										{
											return "{$alias} not available";
										}
									break;
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $createdByCallback, "crud_method", ["ehs", "created_by"]);

							$coWorkerCallback = function( $settings, $coWorker ) use ($iConfig, $alias)
							{
								$ehsId = $settings->getNewValue();

								if( !$this->t_EHSOps_isAvailableByMember($iConfig, $ehsId, $coWorker->getId()) )
								{
									return "{$alias} not available";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $coWorkerCallback, [ "ehs", "co_worker" ]);
						break;

						case "name":
							$createdByCallback = function( $settings, $createdBy ) use ( $iConfig, $alias )
							{
								$id = $settings->spawn("id", null, "ehs")->getCurrentValue();

								if( $this->t_EHSOps_nameExists($iConfig, $settings->getNewValue(), $createdBy->getId(), $id) )
								{
									return "{$alias} already exists";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $createdByCallback, [ "ehs", "created_by" ]);
						break;

						case "icon_file":
							//
							// the right appcomponent must be provided
							// and must be enabled too
							//
							$iComponent = $newValue->getAppComponent();

							if( "5" !== $iComponent->getId() || !$iComponent->isEnabled() )
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
							// must be a png of jpg file
							//
							else if( !$this->t_utilFileOps_hasExtension( $newValue->getUrlPath(), "jpeg", "jpg", "png" ) )
							{
								$this->setLastError("{$alias} invalid format");
							}
						break;

						case "attachment_file":
							$iComponent = $newValue->getAppComponent();

							if( "5" !== $iComponent->getId() || !$iComponent->isEnabled() )
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
							else if( !preg_match("/\.pdf$/i", $newValue->getUrlPath(), $output_array) )
							{
								$this->setLastError("{$alias} must be pdf");
							}
						break;

						case "created_by":
							$existenceValidationErr = function() use ($newValue, $alias)
							{
								return $newValue->getId() ? false : $this->setLastError("{$alias} invalid");
							};

							$existenceValidationErr();
						break;

						case "co_worker":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} invalid");
							}
						break;
					}
				break;

				case "settings":
					switch( $name )
					{
						//noop
					}
				break;
			}
		}
	}

?>