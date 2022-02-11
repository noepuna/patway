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
	use App\File\File as AppFile;
	use App\File\FileMeta as AppFileMeta;
	use App\Construction\EnvironmentHealthSafety\EHSMeta;





	class EHS implements \App\Construction\EnvironmentHealthSafety\IEHS
	{
		use \Core\Util\TUtilOps,
			\App\Construction\EnvironmentHealthSafety\TEHSOps;

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
	    	'ehs' =>
	    	[
				'id' => null,
				'name' => null,
				'description' => null,
				'icon_file' => null,
				'attachment_file' => null,
				'created_by' => null,
				'date_created' => null,
				'enabled' => null,
				'deleted' => null
	    	],
	    	'settings' =>
	    	[
	    		'has_event' => false
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
	    	'create' => [ "crud_method", [ "ehs", "name", "icon_file", "attachment_file", "created_by" ] ],
	    	'read' => 	[ "crud_method", [ "ehs", "id", "created_by" ] ],
	    	'read_by_coworker' => [ "crud_method", [ "ehs", "co_worker" ] ],
	    	'update' => 	[ "crud_method", [ "ehs", "id", "created_by" ] ]
	    ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'ehs' => false, 'settings' => false, 'icon_file' => false, 'attachment_file' => false ];

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
		public function __construct( IConfig $CONFIG, EHSMeta $META )
		{
			$ehs = $META->ehs;

			if( $META->require(...self::_CTOR_REQS['create']) && EHSMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['ehs'] = 
				[
					'name' => $ehs['name'],
					'description' => $ehs['description'] ?? $this->getDescription(),
					'icon_file' => $ehs['icon_file'],
					'attachment_file' => $ehs['attachment_file'],
			        'created_by' => $ehs['created_by']->getId(),
			        'date_created' => $CONFIG->getCurrentTime(),
					'enabled' => true,
					'deleted' => false

				] + $this->_prop['ehs'];
			}
			else if( $META->require(...self::_CTOR_REQS['read']) && EHSMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['ehs'] =
				[
					'id' => $ehs['id']

				] + $this->_prop['ehs'];
			}
			else if( $META->require(...self::_CTOR_REQS['read_by_coworker']) && EHSMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['ehs'] =
				[
					'id' => $ehs['id']

				] + $this->_prop['ehs'];
			}
			else if( $META->require("crud_method", ["ehs", "id"]) && EHSMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop['ehs'] =
				[
					'id' => $ehs['id']

				] + $this->_prop['ehs'];
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
		public static function createInstance( IConfig $CONFIG, EHSMeta $META )
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
	    	return $this->_prop['ehs']['id'] ?? null;
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getName() : string
	    {
	    	$this->_requireEHSSegment();

	    	return $this->_prop['ehs']['name'] ?? null;
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
	    	$this->_requireEHSSegment();

	    	return $this->_prop['ehs']['description'] ?? null;
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getIconFile() : AppFile
        {
            $this->_requireIconSegment();

            return $this->_prop['ehs']['icon_file'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getAttachmentFile() : AppFile
        {
            $this->_requireAttachmentSegment();

            return $this->_prop['ehs']['attachment_file'];
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
            $this->_requireEHSSegment();

            return $this->_prop['ehs']['created_by'];
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
            $this->_requireEHSSegment();

            return $this->_prop['ehs']['date_created'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function hasEvent() :? int
        {
            $this->_requireSettingsSegment();

            return $this->_prop['settings']['has_event'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function isEnabled() : bool
        {
            $this->_requireEHSSegment();

            return $this->_prop['ehs']['enabled'];
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
            $this->_requireEHSSegment();

            return $this->_prop['ehs']['deleted'];
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
        private function _requireEHSSegment()
        {
            if( !$this->_propertySegment['ehs'] && $this->getId() )
            {
                $e = self::t_EHSOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['ehs'] =
                [
					'id' => $e['id'],
					'name' => $e['name'],
					'description' => $e['description'],
					'icon_file_id' => $e['icon'],
					'attachment_file_id' => $e['attachment'],
					'created_by' => $e['created_by'],
					'date_created' => $e['date_created'],
					'enabled' => $e['enabled'],
					'deleted' => $e['deleted']

                ] + $this->_prop['ehs'];

                $this->_propertySegment['ehs'] = true;
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
        private function _requireSettingsSegment()
        {
            if( !$this->_propertySegment['settings'] && $this->getId() )
            {
                $e = self::t_EHSOps_getSettingsById( $this->_config, $this->getId() ) ?? [];

                $this->_prop['settings'] =
                [
					'has_event' => $e['has_event'] ?? false

                ] + $this->_prop['settings'];

                $this->_propertySegment['settings'] = true;
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
        private function _requireIconSegment()
        {
        	$this->_requireEHSSegment();

            if( !$this->_propertySegment['icon_file'] && $this->getId() )
            {
            	$meta =
            	[
            		'crud_method' => AppFileMeta::CRUD_METHOD_READ,
            		'app_file' =>
            		[
            			'id' => $this->_prop['ehs']['icon_file_id']
            		]
            	];

            	$iConfig = $this->_config;
            	$iMeta = AppFileMeta::createInstance( $iConfig, $meta );

            	$this->_prop['ehs']['icon_file'] = AppFile::createInstance( $iConfig, $iMeta );

                $this->_propertySegment['icon_file'] = true;
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
        private function _requireAttachmentSegment()
        {
        	$this->_requireEHSSegment();

            if( !$this->_propertySegment['attachment_file'] && $this->getId() )
            {
            	$meta =
            	[
            		'crud_method' => AppFileMeta::CRUD_METHOD_READ,
            		'app_file' =>
            		[
            			'id' => $this->_prop['ehs']['attachment_file_id']
            		]
            	];

            	$iConfig = $this->_config;
            	$iMeta = AppFileMeta::createInstance( $iConfig, $meta );

            	$this->_prop['ehs']['attachment_file'] = AppFile::createInstance( $iConfig, $iMeta );

                $this->_propertySegment['attachment_file'] = true;
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
			//
			// save the files
			//
			$iIconFile = $this->getIconFile();
			$iAttachmentFile = $this->getAttachmentFile();

			if( $iIconFile->create() && $iAttachmentFile->create() )
			{
				//
				// save the ehs information
				//
				$param['ehs'] =
				[
					'name' => $this->getName(),
					'description' => $this->getDescription(),
					'icon' => $iIconFile->getId(),
					'attachment' => $iAttachmentFile->getId(),
					'created_by' => $this->getCreatedBy(),
					'date_created' => $this->getDateCreated(),
					'enabled' => $this->isEnabled(),
					'deleted' => $this->isDeleted()
				];

				$iDb->query
				("
					INSERT INTO `environment_health_safety`
					( `name`, `description`, `icon`, `attachment`, `created_by`, `date_created`, `enabled`, `deleted` )
					VALUES
					( :name, :description, :icon, :attachment, :created_by, :date_created, :enabled, :deleted )",

					$param['ehs']
				);

				$this->_prop['ehs']['id'] = $iDb->lastInsertId();

				$dbTransaction && $this->getId() && $iDb->commit();

				return $this->getId();
			}

			$dbTransaction && $iDb->rollback();

			return false;
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
		public function update( EHSMeta $META ) : ?bool
		{
	    	//
	    	// save the changes
	    	//
	    	$iDb = $this->_config->getDbAdapter();
	    	$dbTransaction = $iDb->beginTransaction();

	    	$allowedProperties =
	    	[
	    		'ehs' =>
		    	[
		    		[ "name", "name" ],
		    		[ "description", "description" ],
					[ "enabled", "enabled" ],
					[ "deleted", "deleted" ],
		    	],
		    	'settings' =>
		    	[
		    		[ "has_event", "has_event" ]
		    	]
	    	];

	    	$param = $EHSQryFields = [];

	    	foreach( $allowedProperties as $segment => $props )
	    	{
	    		if( !($segmentProp = $META->$segment) )
	    		{
	    			continue;
	    		}

	    		foreach( $props as [$column, $name] )
	    		{
	    			if( array_key_exists($name, $segmentProp) )
	    			{
	    				$param[$segment][$column] = $segmentProp[$name];
	    				$EHSQryFields[$segment][] = $name;
	    			}
	    		}
	    	}

	    	if( $param['ehs'] ?? null )
	    	{
	    		$setQryFields = [];
	    		$param['ehs']['ehs_id'] = $this->getId();

	    		foreach( $EHSQryFields['ehs'] as $key => $column )
	    		{
		    		$setQryFields[] = $column ." = :" . $column;
	    		}

	    		$setQryFields = implode(", ", $setQryFields);

				$iDb->query
				("
					UPDATE `environment_health_safety`
					SET {$setQryFields}
					WHERE `uid` = :ehs_id",

					$param["ehs"]
				);

				$this->_propertySegment['ehs'] = false;
	    	}

	    	if( $param['settings'] ?? null )
	    	{
	    		$param['settings']['ehs_fk'] = $this->getId();
	    		$EHSQryFields['settings'][] = "ehs_fk";

	    		$setQry = "`" . implode("`, `", $EHSQryFields['settings']) . "`";
	    		$valueQry = ":" . implode(", :", $EHSQryFields['settings']);

	    		foreach( $EHSQryFields['settings'] as $key => $column )
	    		{
	    			$onDuplicateKeyUpdateQry[] = "`$column` = VALUES(`$column`)";
	    		}

	    		$onDuplicateKeyUpdateQry = implode(", ", $onDuplicateKeyUpdateQry);

				$iDb->query
				("
					INSERT INTO `environment_health_safety_settings`({$setQry})
					VALUES({$valueQry}) ON DUPLICATE KEY UPDATE {$onDuplicateKeyUpdateQry}",

					$param["settings"]
				);

				$this->_propertySegment['settings'] = false;
	    	}

	    	$dbTransaction && $iDb->commit();

			return true;
		}
	}

?>