<?php

/**
 * ...
 *
 * @category	App\MessagingApp\Messaging\Notification
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Messaging\Notification;

	use App\IConfig,
		App\Messaging\Notification\NotificationMeta;





	class Notification implements \App\Messaging\Notification\INotification
	{
		use \Core\Util\TUtilOps,
			\App\Messaging\TMessagingOps;

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
	    	'notification' =>
	    	[
	    		'id' => null,
	    		'message_id' => null,
				'app_component' => null,
				'title' => null,
				'body' => null,
				'data' => null
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
        private $_propertySegment = [ 'notification' => false ];

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
	    	'create' => [ "crud_method", [ "notification", "message_id", "app_component", "title", "body", "data" ] ],
	    	//'read' => 	[ "crud_method", [ "notification", "id", "created_by" ] ]
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
		public function __construct( IConfig $CONFIG, NotificationMeta $META )
		{
			$n = $META->notification;

			if( $META->require( ...self::_CTOR_REQS['create'] ) && NotificationMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['notification'] =
				[
					'message_id' => $n['message_id'],
			        'app_component' => $n['app_component'],
			        'title' => $n['title'],
			        'body' => $n['body'],
			        'data' => $n['data']

				] + $this->_prop['notification'];

				//
				// create the payload here to catch any errors before hand
				//
			}
			/*else if( $META->require( ...self::_CTOR_REQS['read'] ) && NotificationMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['notification'] =
				[
					'id' => $m['id']

				] + $this->_prop['notification'];
			}
			/*else if( $META->require("crud_method", ["event", "id"]) && EventMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop['event'] =
				[
					'id' => $e['id']

				] + $this->_prop['event'];
			}*/
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
		public static function createInstance( IConfig $CONFIG, NotificationMeta $META )
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
	    public function getId() :? string
	    {
	    	//$this->_requireMessageSegment();

	    	return $this->_prop['notification']['id'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getMessageId() :? string
	    {
	    	//$this->_requireMessageSegment();

	    	return $this->_prop['notification']['message_id'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getAppComponent() : \App\Structure\Component
	    {
	    	//$this->_requireMessageSegment();

	    	return $this->_prop['notification']['app_component'];
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
	    	//$this->_requireRecipientsSegment();

	    	return $this->_prop['notification']['title'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getBody() :? string
	    {
			//$this->_requireMessageSegment();

	    	return $this->_prop['notification']['body'];
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getData() : Array
        {
            //$this->_requireMessageSegment();

            return $this->_prop['notification']['data'];
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
        private function _requireMessageSegment()
        {
            if( !$this->_propertySegment['notification'] && $this->getId() )
            {
                /*$msg = self::t_messagingOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['notification'] =
                [
					'id' => $msg['id'],
					'type' => $msg['type'],
					'single_recipient' => $msg['recipient'],
					'sender' => $msg['created_by'],
					'date_created' => $msg['date_created'],
					'deleted' => $msg['deleted']

                ] + $this->_prop['notification'];

                $this->_propertySegment['sent_message'] = true;*/
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
        private function _createPayloadData()
        {
    		$payload =
    		[
    			'title' => $this->getTitle(),
    			'body' 	=> $this->getBody(),
    			'data' 	=> $this->getData()
    		];

    		return json_encode($payload);
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

			$param['notification'] =
			[
				'msg_id' => $this->getMessageId(),
				'app_component' => $this->getAppComponent()->getId(),
				'payload' => $this->_createPayloadData()
			];

			//
			// save the notification
			//
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			$iDb->query
			("
				INSERT INTO `notification`
				(`message_fk`, `app_component_fk`, `payload`)
				VALUES
				(:msg_id, :app_component, :payload)",

				$param['notification']
			);

			$this->_prop['notification']['id'] = $iDb->lastInsertId();;

			$dbTransaction && $this->getId() && $iDb->commit();

			return $this->getId();
		}
	}

?>