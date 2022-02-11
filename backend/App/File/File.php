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
	use App\File\FileMeta as AppFileMeta;





	class File extends \App\File\XFile
	{
	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>
	     * @access private
	     */
	    private Array $_prop =
	    [
	    	'crud_method' => null,
	    	'app_file' =>
	    	[
	    		'url_path' => null
	    	]
	    ];

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var string
	     * @access private
	     */
	    private const _API_DIR = "api/files";

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'app_file' => false ];

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
		 *	...
		 *
		 * @access public
		 * @static
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function __construct( IConfig $CONFIG, FileMeta $META )
		{
			$f = $META->app_file;

			if( FileMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
				$iFileUpload = $f['file_upload'];

				$tmpPathInfo = pathinfo($iFileUpload->getFilePath());
				$filePathInfo = pathinfo($iFileUpload->getFilename());

				$this->_prop['app_file'] =
				[
					//
					// create temporary url. will be replaced by a unique file id during Create method.
					// temporary url will use tmp_name of upload file which is already unique in itself
					//
					'url_path' => $CONFIG->getBaseURL() . "/" . self::_API_DIR . "/" . $tmpPathInfo['filename'] . "." . $filePathInfo['extension']

				] + $this->_prop['app_file'];
			}

			$this->_prop =
			[
				'crud_method' => $META->crud_method

			] + $this->_prop;

			$this->_config = $CONFIG;

			Parent::__construct($CONFIG, $META);
		}





		/**
		 *	...
		 *
		 * @access public
		 * @static
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public static function createInstance( IConfig $CONFIG, FileMeta $META )
		{
			try
			{
				return new self($CONFIG, $META);
			}
			catch( \Exception $EXCEPTION )
			{
				return null;
			}
		}





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getURLPath() : string
        {
            $this->_requireAppFileSegment();

            return $this->_prop['app_file']['url_path'];
        }





        /**
         *  ...
         *
         * @access private
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        private function _requireAppFileSegment()
        {
            if( !$this->_propertySegment['app_file'] && $this->getId() )
            {
                $this->_prop['app_file'] =
                [
					'url_path' => Parent::getURLPath()

                ] + $this->_prop['app_file'];

                $this->_propertySegment['app_file'] = true;
            }
        }





        /**
         *  ...
         *
         * @access private
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        private function _getUniqueFilename( int $FILE_ID )
        {
			//
			// check if file exists
			//
			$baseFractions = [ "A", "B", 'C', 'D', 'E', 'F' ];
			$base = $baseFractions[ intval($FILE_ID / 357913941) ];
			//
			// convert the file id into hex
			//
			$hexId = dechex( $this->getId() );
			//
			// get file extension
			//
			$iFileUpload = $this->getFileUpload();

			list($ext) = array_keys( AppFileMeta::_EXTN, $iFileUpload->getType() );

			return $base . $hexId . '.' . $ext;
        }





		/**
		 *	...
		 *
		 * @access public
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function create() : ?string
		{
			//
			// save the data
			//
			$iConfig = $this->_config;
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			if( !Parent::create() )
			{
				return false;
			}
			//
			// check directory exists
			//
			$uploadDir = $iConfig->getDocumentRoot() . "/" . self::_API_DIR;

			if( !is_dir($uploadDir) )
			{
				return false;
			}
			//
			// change filename to unique one and store the file
			//
			if( $fileId = $this->getId() )
			{
				$uniqueFilename = $this->_getUniqueFilename( $this->getId() );

				$param =
				[
					'uid' => $fileId,
					'url_path' => $iConfig->getBaseURL() . "/" . self::_API_DIR . "/" . $uniqueFilename,
					'created_by' => $this->getCreatedBy()
				];

				$iDb->query( "UPDATE `app_files` SET `url_path` = :url_path WHERE `uid` = :uid AND `created_by` = :created_by", $param );

				if( $iDb->rowCount() )
				{
					$iFileUpload = $this->getFileUpload();
					$urlPathinfo = pathinfo( $param['url_path'] );
					$filePath = $uploadDir . "/" . $urlPathinfo['filename'] . "." . $urlPathinfo['extension'];

					if( move_uploaded_file($iFileUpload->getFilePath(), $filePath) )
					{
						$dbTransaction && $iDb->commit();

						return $this->getId();
					}
				}
			}

			return false;
		}
	}

?>