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
	use App\Structure\Component as AppComponent;
	use App\Structure\ComponentMeta as AppComponentMeta;
	use App\File\FileMeta as AppFileMeta;





	Abstract class XFile implements \App\File\IFile
	{
		use \Core\Util\TUtilOps,
			\App\File\TAppFileOps;

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
				'id' => null,
				'app_component' => null,
				'app_component_id' => null,
				'visibility' => null,
				'title' => null,
				'description' => null,
				'file_upload' => null,
				'url_path' => null,
				'created_by' => null,
				'date_created' => null,
				'deleted' => false
	    	]
	    ];

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>
	     * @access private
	     */
	    private const _CTOR_REQS =
	    [
	    	'create' => [ "crud_method", [ "app_file", "app_component", "file_upload", "visibility", "created_by" ] ],
	    	'read' => [ "crud_method", [ "app_file", "id" ] ]
	    ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'app_file' => false, 'app_component' => false ];

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

			if( $META->require( ...self::_CTOR_REQS['create'] ) && FileMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
				$iFileUpload = $f['file_upload'];

				$this->_prop['app_file'] =
				[
					'file_upload' => $iFileUpload,
					'url_path' => $CONFIG->getBaseURL() . "/" . $iFileUpload->getFileName(),
					'app_component' => $f['app_component'],
					'visibility' => $f['visibility'],
					'created_by' => $f['created_by']->getId(),
					'date_created' => $CONFIG->getCurrentTime()

				] + $this->_prop['app_file'];
			}
			else if( $META->require( ...self::_CTOR_REQS['read'] ) && FileMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['app_file'] =
				[
					'id' => $f['id']

				] + $this->_prop['app_file'];
			}
			else
			{
				throw new \Exception("Invalid meta", 1);
			}

			$this->_prop =
			[
				'crud_method' => $META->crud_method

			] + $this->_prop;

			$this->_config = $CONFIG;
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
	    public function getId() : ?string
	    {
	    	return $this->_prop['app_file']['id'] ?? null;
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getAppComponent() : AppComponent
	    {
	    	return $this->_prop['app_file']['app_component'] ?? null;
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getVisibility() :? string
        {
            $this->_requireAppFileSegment();

            return $this->_prop['app_file']['visibility'];
        }






        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getTitle() :? string
        {
            $this->_requireAppFileSegment();

            return $this->_prop['app_file']['title'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDescription() :? string
        {
            $this->_requireAppFileSegment();

            return $this->_prop['app_file']['description'];
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
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getCreatedBy() : string
        {
            $this->_requireAppFileSegment();

            return $this->_prop['app_file']['created_by'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDateCreated() : int
        {
            $this->_requireAppFileSegment();

            return $this->_prop['app_file']['date_created'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function isDeleted() : bool
        {
            $this->_requireAppFileSegment();

            return !!$this->_prop['app_file']['deleted'];
        }





        /**
         * ...
         *
         * @access protected
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        protected function getFileUpload() :? \Core\HTTP\FileUpload
        {
            return $this->_prop['app_file']['file_upload'];
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
                $f = self::t_appFileOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['app_file'] =
                [
					'id' => $f['id'],
					'visibility' => $f['visibility_id'],
					'app_component_id' => $f['app_component_id'],
					'title' => $f['title'],
					'description' => $f['description'],
					'url_path' => $f['url_path'],
					'created_by' => $f['created_by'],
					'date_created' => $f['date_created'],
					'deleted' => $f['deleted']

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
        private function _requireComponentSegment()
        {
        	$this->_requireAppFileSegment();

            if( !$this->_propertySegment['structure'] && $this->getId() )
            {
                $meta =
                [
                	'crud_method' => AppStructureMeta::CRUD_METHOD_READ,
                	'app_structure' =>
                	[
                		'id' => $this->_prop['app_component']['structure_id']
                	]
                ];

                $iMeta = AppStructureMeta::createInstance( $this->_config, $meta );

                $this->_prop['app_component']['structure'] = AppStructure::createInstance( $this->_config, $iMeta );

                $this->_propertySegment['structure'] = true;
            }
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
			$iConfig = $this->_config;
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			$param['app_file'] =
			[
				'visibility' => $this->getVisibility(),
				'app_component_fk' => $this->getAppComponent()->getId(),
				'title' => $this->getTitle(),
				'description' => $this->getDescription(),
				'url_path' => $this->getURLPath(),
				'created_by' => $this->getCreatedBy(),
				'date_created' => $this->getDateCreated(),
				'deleted' => $this->isDeleted()
			];
			//
			// save the data
			//
			$iDb->query
			("
				INSERT INTO `app_files`
				(`visibility_fk`, `app_component_fk`, `title`, `description`, `url_path`, `created_by`, `date_created`, `deleted`)
				VALUES
				(:visibility, :app_component_fk, :title, :description, :url_path, :created_by, :date_created, :deleted)",

				$param['app_file']
			);

			$this->_prop['app_file']['id'] = $iDb->lastInsertId();

			$dbTransaction && $this->getId() && $iDb->commit();

			return $this->getId();
		}
	}

?>