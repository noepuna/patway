<?php

	/**
	 * ...
	 *
	 * @category	App\BehaviorBaseSafety\Messaging;
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\BehaviorBaseSafety\Messaging;

	use App\IConfig;
	use App\BehaviorBaseSafety\Messaging\SentMessageMeta;





	class SentMessage extends \App\Messaging\SentMessage implements \App\BehaviorBaseSafety\Messaging\ISentMessage 
	{
		use \Core\Util\TUtilOps;

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
	    	'bbs_observation_message' =>
	    	[
				'observation_id' => null
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
        private $_propertySegment = [ 'message' => false ];

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
	    	'create' => [ "crud_method", [ "bbs_observation_message", "observation_id" ] ],
	    	'read' => 	[ "crud_method", [ "message" ] ]
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
			$oM = $META->bbs_observation_message;

			if( $META->require( ...self::_CTOR_REQS['create'] ) && SentMessageMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['bbs_observation_message'] = 
				[
			        'observation_id' => $oM['observation_id']

				] + $this->_prop['bbs_observation_message'];
			}
			/*else if( $META->require("crud_method", ["event", "id"]) && EventMeta::CRUD_METHOD_READ === $META->crud_method )
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

			] + $this->_prop;*/

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
	    public function getObservation() : string
	    {
	    	return $this->_prop['bbs_observation_message']['observation_id'];
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
			if( !($messageId = Parent::create()) )
			{
				return false;
			}

			$param['bbs_observation_message'] =
			[
				'message_fk' => $messageId,
				'bbs_observation_fk' => $this->getObservation(),
			];

			$iConfig = $this->_config;

			//
			// save the data
			//
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			$iDb->query
			("
				INSERT INTO `bbs_observation_messages`
				(`bbs_observation_fk`, `message_fk`)
				VALUES
				(:bbs_observation_fk, :message_fk)",

				$param['bbs_observation_message']
			);

			$dbTransaction && $iDb->commit();

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
		/*public function update( EventMeta $META ) : ?bool
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
		}*/
	}

?>