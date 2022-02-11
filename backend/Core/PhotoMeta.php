<?php

/**
 * ...
 *
 * @category	Accounts
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace Resource;

	use Resource\IConfig;
	use Resource\AppMeta;

	Class PhotoMeta extends AppMeta {

    /**
     * ...
     *
     * ...
     *
     * @var Resource\IConfig;
     * @access private
     */

    	private $_config;

    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */

    	private $_prop = array();

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

		public function __construct( IConfig $CONFIG )
		{
			$this->_config = $CONFIG;

			$this->_nonEmptyFields = array
			(
 				"name" 	=> array( 'type' => "string", "length-max" => 16 ),
 				"type", // application/x-php
 				"tmp_name", ///opt/lampp/temp/phpck5vA2
 				"size" 	=> array( 'type' => "numeric" ),
            	"upload_error" => array( 'type' => "scalar" )
            //[size] => 1293
			);
		}

	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 *	...
	 *	...
	 *	...
	 */

		private function _checkFileUploadError( string $ERROR_CODE )
		{
			switch( $ERROR_CODE )
			{
				case UPLOAD_ERR_OK:
					// Value: 0; There is no error, the file uploaded with success.
					// noop;
					return false;
				break;

				case UPLOAD_ERR_INI_SIZE:
					// Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
					return "photo must not exceed " . ini_get('upload_max_filesize');
				break;

				case UPLOAD_ERR_FORM_SIZE:
					// Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
					return "maximum file size reached";
				break;

				case UPLOAD_ERR_PARTIAL:
					//Value: 3; The uploaded file was only partially uploaded.
					return "file is partially uploaded";
				break;

				case UPLOAD_ERR_NO_FILE:
					//Value: 4; No file was uploaded.
					return "no file was uploaded";
				break;

				case UPLOAD_ERR_NO_TMP_DIR:
					//Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3.
					return "opps. system's upload location is missing";
				break;

				case UPLOAD_ERR_CANT_WRITE:
					//Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.
					return "opps. system's uploaded location is unavailable";
				break;

				case UPLOAD_ERR_EXTENSION:
					//Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.
					return "opps. upload stopped";
				break;

				default:
					return "oops. unkwown error occured";
			}
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

		protected function _setSpecialProperty( string $NAME, $VALUE )
		{
			switch( $NAME )
			{
				case "type":
					$format = array( "image/jpeg", "image/png", "image/gif" );

					if( false === in_array($VALUE, $format) )
					{
						$this->error = "format is invalid";
					}
					break;

				case "upload_error":
					$uploadError = $this->_checkFileUploadError($VALUE);

					if( $uploadError )
					{
						$this->error = $uploadError;
					}

					break;

				default:
					# noop
			}
		}
	}

?>