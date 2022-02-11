<?php

	/**
	 * ...
	 *
	 * @category	App\Construction\EnvironmentHealthSafety\Messaging
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Construction\EnvironmentHealthSafety\Messaging;

	use App\IConfig,
		App\File\File as AppFile,
		//App\File\FileMeta as AppFileMeta,
		App\Construction\EnvironmentHealthSafety\Messaging\SentMessageMeta;





	class SentMessage extends \App\Messaging\SentMessage implements \App\Construction\EnvironmentHealthSafety\Messaging\ISentMessage 
	{
		use \Core\Util\TUtilOps,
			\App\Construction\EnvironmentHealthSafety\TEHSOps,
			\App\Construction\EnvironmentHealthSafety\Messaging\TEHSMessagingOps;

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
	    	'ehs_message' =>
	    	[
	    		'ehs' => null,
	    		'status' => null,
				'title' => null,
				'location' => null,
				'description' => null,
				'risk_level' => null,
				'date_start' => null,
				'date_end' => null
	    	]
	    ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'ehs' => false, 'ehs_message' => false ];

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
	    	'create' =>
	    	[
	    		"crud_method",
	    		[
	    			"ehs_message",
	    			"ehs",
	    			"status",
					"title",
					"location",
					"risk_level",
					"date_start",
					"date_end"
	    		]
	    	],
	    	'read' => [ "crud_method" ],
	    	'update' => [ "crud_method" ]
	    ];

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
		public function __construct( IConfig $CONFIG, SentMessageMeta $META )
		{
			$EHSM = $META->ehs_message;

			if( $META->require( ...self::_CTOR_REQS['create'] ) && SentMessageMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['ehs_message'] = 
				[
			        'ehs' => $EHSM['ehs'],
			        'status' => $EHSM['status'],
					'title' => $EHSM['title'],
					'location' => $EHSM['location'],
					'description' => $EHSM['description'] ?? $this->getDescription(),
					'risk_level' => $EHSM['risk_level'],
					'date_start' => $EHSM['date_start'],
					'date_end' => $EHSM['date_end']

				] + $this->_prop['ehs_message'];
			}
			else if( $META->require( ...self::_CTOR_REQS['read'] ) && SentMessageMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				//
				// noop
				//
			}
			else if( $META->require( ...self::_CTOR_REQS['update'] ) && SentMessageMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				//
				// noop
				//
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

			Parent::__construct( $CONFIG, $META );
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
		public static function createInstance( IConfig $CONFIG, \App\Messaging\SentMessageMeta $META )
		{
			assert($META instanceof SentMessageMeta);

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
	    public function getEHS() : string
	    {
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['ehs'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getStatus() : string
	    {
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['status'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getTitle() : string
	    {
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['title'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getLocation() : string
	    {
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['location'];
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
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['description'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getRiskLevel() : string
	    {
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['risk_level'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getDateStart() : string
	    {
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['date_start'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getDateEnd() :? string
	    {
	    	$this->_requireEHSMessageSegment();

	    	return $this->_prop['ehs_message']['date_end'];
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
        private function _requireEHSMessageSegment()
        {
        	$crudRequirement = [ SentMessageMeta::CRUD_METHOD_READ, SentMessageMeta::CRUD_METHOD_UPDATE ];

            if( !$this->_propertySegment['ehs_message'] && $this->getId() && in_array($this->_prop['crud_method'], $crudRequirement) )
            {
                $m = self::t_EHSMessagingOps_getInfoById( $this->_config, $this->getId() );
                //$ehs = self::t_EHSOps_getInfoById( $this->_config, $m['ehs'] );

                /*$this->_prop['ehs_message']['ehs'] = new Class($this->_config, $ehs) extends \App\Construction\EnvironmentHealthSafety\EHS
                {
                	private $_config;

                	private $_id;
                	private $_attachment_file_id;
                	private $_created_by;
                	private $_date_created;
                	private $_deleted;


                	public function __construct( $config, $data )
                	{
                		$this->_config = $config;

                		$this->_id = $data['id'];
                		$this->_attachment_file_id = $data['attachment_file'];
                		$this->_created_by = $data['created_by'];
                		$this->_date_created = $data['date_created'];
                		$this->_deleted = $data['deleted'];
                	}

                	public function getId() : string
                	{
                		return $this->_id;
                	}

				    public function getAttachmentFile() : \App\File\File
				    {
		            	$meta =
		            	[
		            		'crud_method' => AppFileMeta::CRUD_METHOD_READ,
		            		'app_file' =>
		            		[
		            			'id' => $this->_attachment_file_id
		            		]
		            	];

		            	$iConfig = $this->_config;
		            	$iMeta = AppFileMeta::createInstance( $iConfig, $meta );

		            	return AppFile::createInstance( $iConfig, $iMeta );
				    }

				    public function getCreatedBy() : string
				    {
				    	return $this->_created_by;
				    }

				    public function getDateCreated() : int
				    {
				    	return $this->_date_created;
				    }

				    public function isDeleted() : bool
				    {
				    	return $this->_deleted;
				    }
                };*/

                $this->_prop['ehs_message'] =
                [
	    			'ehs' => $m['ehs_id'],
	    			'status' => $m['status_id'],
					'title' => $m['title'],
					'location' => $m['location'],
					'risk_level' => $m['risk_level_id'],
					'date_start' => $m['date_start'],
					'date_end' => $m['date_end'],
					'description' => $m['description']

                ] + $this->_prop['ehs_message'];

                $this->_propertySegment['ehs_message'] = true;
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
			if( !($messageId = Parent::create()) )
			{
				return false;
			}

			$param['ehs_message'] =
			[
				'message' => $messageId,
				'status' => $this->getStatus(),
				'ehs' => $this->getEHS(),
				'title' => $this->getTitle(),
				'location' => $this->getLocation(),
				'description' => $this->getDescription(),
				'risk_level' => $this->getRiskLevel(),
				'date_start' => $this->getDateStart(),
				'date_end' => $this->getDateEnd()
			];

			$iConfig = $this->_config;

			//
			// save the data
			//
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			$iDb->query
			("
				INSERT INTO `environment_health_safety_messages`
				(`message_fk`, `status_fk`, `ehs_fk`, `title`, `location`, `description`, `risk_level_fk`, `date_start`, `date_end`)
				VALUES
				(:message, :status, :ehs, :title, :location, :description, :risk_level, :date_start, :date_end )",

				$param['ehs_message']
			);

			$dbTransaction && $iDb->commit();

			return $this->getId();
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
		public function update( \App\Messaging\SentMessageMeta $META ) : ?bool
		{
			assert( $META instanceof SentMessageMeta );

	    	//
	    	// save the changes
	    	//
	    	$iDb = $this->_config->getDbAdapter();
	    	$dbTransaction = $iDb->beginTransaction();

	    	$allowedProperties['ehs_message'] =
	    	[
	    		[ "status_fk", "status" ],
	    		//[ "ehs_fk", "ehs" ],
				[ "title", "title" ],
				[ "location", "location" ],
				[ "description", "description" ],
				[ "risk_level_fk", "risk_level" ],
				[ "date_start", "date_start" ],
				[ "date_end", "date_end" ]
	    	];

	    	$param = $EHSMsgQryFields['set'] = [];

	    	foreach($allowedProperties as $segment => $props)
	    	{
	    		if( !($segmentProp = $META->$segment) )
	    		{
	    			continue;
	    		}

	    		foreach( $props as [$column, $name] )
	    		{
	    			if( array_key_exists($name, $segmentProp) )
	    			{
	    				$param[$segment][$name] = $segmentProp[$name];
	    				$EHSMsgQryFields['set'][$segment][] = "`{$column}` = :{$name}";
	    			}
	    		}
	    	}

			if( !Parent::update($META) )
			{
				$dbTransaction && $iDb->rollback();

				return false;
			}

	    	if( $param['ehs_message'] ?? null )
	    	{
	    		$param['ehs_message']['message_id'] = $this->getId();
	    		$EHSMsgQryFragment['set'] = implode(", ", $EHSMsgQryFields['set']['ehs_message']);

				$iDb->query
				("
					UPDATE `environment_health_safety_messages`
					SET {$EHSMsgQryFragment['set']}
					WHERE `message_fk` = :message_id",

					$param["ehs_message"]
				);

				$this->_propertySegment['ehs_message'] = false;
	    	}

	    	$dbTransaction && $iDb->commit();

	    	return true;
		}
	}

?>