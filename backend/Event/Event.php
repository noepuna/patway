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
	use App\Event\EventMeta;





	class Event implements \App\Event\IEvent
	{
		use \Core\Util\TUtilOps,
			\App\Event\TEventOps;

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
	    	'event' =>
	    	[
				'id' => null,
				'title' => null,
				'description' => null,
				'location' => null,
				'closed' => false,
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
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'event' => false ];

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
		public function __construct( IConfig $CONFIG, EventMeta $META )
		{
			$e = $META->event;

			if( $META->require("crud_method", ["event", "title", "location", "created_by"]) && EventMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['event'] = 
				[
			        'title' => $e['title'],
			        'location' => $e['location'],
			        'description' => $e['description'] ?? null,
			        'created_by' => $e['created_by']->getId(),
			        'date_created' => $CONFIG->getCurrentTime()

				] + $this->_prop['event'];
			}
			else if( $META->require("crud_method", ["event", "id"]) && EventMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['event'] =
				[
					'id' => $e['id']

				] + $this->_prop['event'];
			}
			else if( $META->require("crud_method", ["event", "id"]) && EventMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop['event'] =
				[
					'id' => $e['id']

				] + $this->_prop['event'];
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
		public static function createInstance( IConfig $CONFIG, EventMeta $META )
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
	    	return $this->_prop['event']['id'] ?? null;
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
            $this->_requireEventSegment();

            return $this->_prop['event']['title'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDescription() : ?string
        {
            $this->_requireEventSegment();

            return $this->_prop['event']['description'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getLocation() : ?string
        {
            $this->_requireEventSegment();

            return $this->_prop['event']['location'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function isClosed() : bool
        {
            $this->_requireEventSegment();

            return !!$this->_prop['event']['closed'];
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
            $this->_requireEventSegment();

            return $this->_prop['event']['created_by'];
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
            $this->_requireEventSegment();

            return $this->_prop['event']['date_created'];
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
            $this->_requireEventSegment();

            return $this->_prop['event']['deleted'];
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
        private function _requireEventSegment()
        {
            if( !$this->_propertySegment['event'] && $this->getId() )
            {
                $e = self::t_eventOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['event'] =
                [
					'id' => $e['id'],
					'title' => $e['title'],
					'description' => $e['description'],
					'location' => $e['location'],
					'closed' => $e['closed'],
					'created_by' => $e['created_by'],
					'date_created' => $e['date_created'],
					'deleted' => $e['deleted']
                ] + $this->_prop['event'];

                $this->_propertySegment['event'] = true;
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
			$errors = [];
			$iConfig = $this->_config;

			/*
			 * require additional event properties
			 */
			$eventMeta =
			[
                'crud_method' => $this->_prop['crud_method'],
				'account' =>
				[
					'closed' => $this->isClosed(),
					'date_created' => $this->getDateCreated(),
					'deleted' => $this->isDeleted(),
				]
			];

			$iEventMeta = EventMeta::createInstance($iConfig, $eventMeta);

			if( $iEventMeta instanceof EventMeta )
			{
				$param['event'] =
				[
					'title' => $this->getTitle(),
					'description' => $this->getDescription(),
					'location' => $this->getLocation(),
					'closed' => $this->isClosed(),
					'created_by' => $this->getCreatedBy(),
					'date_created' => $this->getDateCreated(),
					'deleted' => $this->isDeleted()
				];
			}
			else
			{
				return null;
			}

			/*
			 * save the data
			 */
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			$iDb->query
			("
				INSERT INTO `event`
				(`title`, `description`, `location`, `closed`, `created_by`, `date_created`, `deleted`)
				VALUES
				(:title, :description, :location, :closed, :created_by, :date_created, :deleted)",

				$param['event']
			);

			$this->_prop['event']['id'] = $iDb->lastInsertId();

			$dbTransaction && $this->getId() && $iDb->commit();

			return $this->getId();
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
		public function update( EventMeta $META ) : ?bool
		{
			//
			// requiring crud_method, id and created_by is essential in validating other event properties
			//
			$updateRequirements =
			[
				"crud_method",
				[
					"event", "id", "created_by"
				]
			];

			//
			// crud_method must be update
			// id must be equals the value during the class construction
			//
			if( $META->require(...$updateRequirements) && $META->crud_method === EventMeta::CRUD_METHOD_UPDATE )
			{
				$event = $META->event;
				$eventId = $event['id'] ?? null;

				if( $this->getId() !== $eventId )
				{
					return false;
				}
			}
			else
			{
				return false;
			};

			//
			// certain properties can only be made changable
			//
			// synopsis: [ database table column name => property name ]
			//
	    	$allowedProperties['event'] =
	    	[
	    		[ "title", "title" ],
	    		[ "description", "description" ],
	    		[ "location", "location" ],
	    		[ "closed", "closed" ],
	    		[ "deleted", "deleted" ]
	    	];

	    	$param = $fieldSetQry = [];

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
	    				$fieldSetQry[$segment][] = "`{$column}` = :{$name}";
	    			}
	    		}
	    	}

	    	//
	    	// empty properties
	    	//
	    	if( !$param )
	    	{
	    		return null;
	    	}

	    	//
	    	// save the changes
	    	//
	    	$iDb = $this->_config->getDbAdapter();
	    	$dbTransaction = $iDb->beginTransaction();

	    	if( $param['event'] ?? null )
	    	{
	    		$param['event']['uid'] = $this->getId();
	    		$param['event']['created_by'] = $this->getCreatedBy();
	    		$eventSetQry = implode(", ", $fieldSetQry['event']);

				$iDb->query
				("
					UPDATE `event` SET {$eventSetQry}
					WHERE `uid` = :uid AND `created_by` = :created_by",

					$param["event"]
				);

				$this->_propertySegment['event'] = false;
	    	}

	    	$dbTransaction && $iDb->commit();

	    	return true;
		}
	}

?>