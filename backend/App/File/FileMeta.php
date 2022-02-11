<?php

	/**
	 * ...
	 *
	 * @category	App\File
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\File;

	use App\IConfig;





	Class FileMeta extends \App\AppMeta
	{
		use \Core\Util\TUtilOps,
			\App\File\TAppFileOps;

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
			'app_component' =>
			[
				'type' => "\App\Structure\Component",
				'null-allowed' => false,
			],
			'file_upload' =>
			[
				'type' => "\Core\HTTP\FileUpload",
				'null-allowed' => false
			],
			'title' =>
			[
				'type' => "string",
				'length-max' => 64,
				'null-allowed' => true
			],
			'description' =>
			[
				'type' => "string",
				'length-max' => 255,
				'null-allowed' => true
			],
			'url_path' =>
			[
				'type' => "string",
				'length-max' => 128,
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
				'app_file' => self::_metadata
			];
		}





	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var App\IConfig;
	     * @access private
	     */
	    public const _EXTN =
	    [
	    	'jpeg' 	=> "image/jpeg",
	    	'png' 	=> "image/png",
	    	'icon' 	=> "image/x-icon",
	    	'pdf' 	=> "application/pdf"
	    ];





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
				case "app_file":
					switch( $name )
					{
						case "id":
							if( !$this->t_appFileOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$callback = function($settings, $crudMethod, $created_by) use ($iConfig, $alias)
							{
								$observationId = $settings->getNewValue();

								switch( $crudMethod )
								{
									case FileMeta::CRUD_METHOD_READ:
										if( !$this->t_appFileOps_isAvailable($iConfig, $observationId, $created_by->getId()) )
										{
											return "{$alias} not available";
										}
									break;
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method", ["app_file", "created_by"]);
						break;

						case "app_component":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} invalid");
							}
						break;

						case "file_upload":
							if( $uploadErr = $newValue->getError() )
							{
								switch( $uploadErr )
								{
									case UPLOAD_ERR_OK:
										// noop
									break;

									case UPLOAD_ERR_NO_FILE:
										$this->setLastError("no file sent");
									break;

									case UPLOAD_ERR_INI_SIZE:
									case UPLOAD_ERR_FORM_SIZE:
										$this->setLastError("exceeded filesize limit");

									default:
										$this->setLastError("unknown error");
								}
							}
							else if( !in_array($newValue->getType(), self::_EXTN) )
							{
								$this->setLastError("extension is not supported");
							}
							else if( $newValue->getSize() >= $iConfig->getMaxUploadSize() )
							{
								//php_flag display_startup_errors off
								$this->setLastError("size is too big");
							}
						break;

						case "visibility":
							if( !$this->t_appFileOps_visibilityExists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} is not supported");
							}
						break;

						case "created_by":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} invalid");
							}
						break;
					}
				break;
			}
		}
	}

?>