<?php

/**
 * ...
 *
 * @category	App\Messaging
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Messaging;

	use App\IConfig,
		App\Messaging\Recipient,
		App\Messaging\Recipients,
		App\Messaging\SentMessageMeta;





	class SentMessage implements \App\Messaging\ISentMessage
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
	    	'sent_message' =>
	    	[
				'type' => null,
				'message' => null,
				'conversation' => null,
				'recipients' => null,
				'group' => null,
				'sender' => null,
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
        private $_propertySegment = [ 'sent_message' => false, 'recipients' => false ];

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
	    	'create' => [ "crud_method", [ "message", "type", "sender" ] ],
	    	'update' => [ "crud_method", [ "message", "id", "sender" ] ],
	    	'read' => 	[ "crud_method", [ "message", "id", "account_for_access" ] ]
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
			$m = $META->message;

			if( $META->require( ...self::_CTOR_REQS['create'] ) && SentMessageMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['sent_message'] = 
				[
			        'type' => $m['type'],
			        'message' => $m['message'] ?? $this->getMessage(),
			        'conversation' => $m['conversation'] ?? $this->getConversation(),
			        'recipients' => $m['recipients'] ?? [],
			        'group' => $m['group'] ?? null,
			        'sender' => $m['sender']->getId(),
			        'date_created' => $CONFIG->getCurrentTime()

				] + $this->_prop['sent_message'];
			}
			else if( $META->require( ...self::_CTOR_REQS['read'] ) && SentMessageMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['sent_message'] =
				[
					'id' => $m['id']

				] + $this->_prop['sent_message'];
			}
			else if( $META->require( ...self::_CTOR_REQS['update'] ) && SentMessageMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop['sent_message'] =
				[
					'id' => $m['id']

				] + $this->_prop['sent_message'];
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
		public static function createInstance( IConfig $CONFIG, SentMessageMeta $META )
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
	    	return $this->_prop['sent_message']['id'] ?? null;
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getType() : string
	    {
	    	$this->_requireMessageSegment();

	    	return $this->_prop['sent_message']['type'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getMessage() :? string
	    {
	    	$this->_requireMessageSegment();

	    	return $this->_prop['sent_message']['message'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getConversation() :? string
	    {
	    	$this->_requireMessageSegment();

	    	return $this->_prop['sent_message']['conversation'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getRecipients() : \App\Messaging\Recipients
	    {
	    	$this->_requireRecipientsSegment();

	    	return $this->_prop['sent_message']['recipients'];
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getGroup() :? string
        {
            $this->_requireMessageSegment();

            return $this->_prop['sent_message']['group'];
        }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getSender() : string
	    {
			$this->_requireMessageSegment();

	    	return $this->_prop['sent_message']['sender'];
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
            $this->_requireMessageSegment();

            return $this->_prop['sent_message']['date_created'];
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
            $this->_requireMessageSegment();

            return $this->_prop['sent_message']['deleted'];
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
            if( !$this->_propertySegment['sent_message'] && $this->getId() )
            {
                $msg = self::t_messagingOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['sent_message'] =
                [
					'id' => $msg['id'],
					'type' => $msg['type'],
					'sender' => $msg['created_by'],
					'date_created' => $msg['date_created'],
					'deleted' => $msg['deleted']

                ] + $this->_prop['sent_message'];

                $this->_propertySegment['sent_message'] = true;
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
        private function _requireRecipientsSegment()
        {
        	$this->_requireMessageSegment();

        	if( !$this->_propertySegment['recipients'] )
        	{
        		$iRecipients = new Recipients;

	            if( $this->getId() )
	            {
	            	//
	            	// process single recipient message
	            	//
	            	$iSingleRecipient = $iRecipients[] = new Recipient;

	            	$iSingleRecipient->id = $this->_prop['sent_message']['single_recipient'];

	                //
	                // process multiple recipient message
	                //
	                # code here
	            }
	            else
	            {
	            	foreach( $this->_prop['sent_message']['recipients'] as $key => $iRecipient )
	            	{
	            		$iRecipients[$key] = $iRecipient;
	            	}
	            }

	            $this->_prop['sent_message']['recipients'] = $iRecipients;
	            $this->_propertySegment['sent_message'] = true;
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

			$param['sent_message'] =
			[
				'type_fk' => $this->getType(),
				'message' => $this->getMessage(),
				'conversation' => $this->getConversation(),
				//'group' => $this->getGroup(),
				'created_by' => $this->getSender(),
				'date_created' => $this->getDateCreated(),
				'deleted' => $this->isDeleted()
			];

			//
			// save the message
			//
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			$iDb->query
			("
				INSERT INTO `message`
				(`message_type_fk`, `message`, `conversation`, `created_by`, `date_created`, `deleted`)
				VALUES
				(:type_fk, :message, :conversation, :created_by, :date_created, :deleted)",

				$param['sent_message']
			);

			$messageId = $iDb->lastInsertId();

			//
			// save the recipients
			//
			if( count($recipients = $this->getRecipients()) )
			{
				$param = [];
				$valuesFrag = [];
				$recipients = $this->getRecipients();

				foreach( $recipients as $key => $iRecipient )
				{
					$messageKey = 'm' . $key;
					$recipientKey = 'r' . $key;

					$param[$messageKey] = $messageId;
					$param[$recipientKey] = $iRecipient->getId();

					$valuesFrag[] = ':' . $messageKey . ", :" . $recipientKey;
				}

				$iDb->query
				("
					INSERT INTO `message_recipients`(`message_fk`, `recipient_fk`)
					VALUES (" . implode("), (", $valuesFrag) . ")",

					$param
				);
			}

			$this->_prop['sent_message']['id'] = $messageId;

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
		public function update( SentMessageMeta $META ) : ?bool
		{
			//
			// requiring crud_method, id and sender is essential in validating other message properties
			//
			$updateRequirements =
			[
				"crud_method",
				[
					"message",
					"id",
					"sender"
				]
			];

			//
			// crud_method must be update
			// id must be equals the value during the class construction
			//
			if( $META->require(...$updateRequirements) && $META->crud_method === SentMessageMeta::CRUD_METHOD_UPDATE )
			{
				$message = $META->message;

				if( $this->getId() !== ($message['id'] ?? null) )
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
	    	$allowedProperties['message'] =
	    	[
	    		[ "message", "message" ],
	    		[ "deleted", "deleted" ]
	    	];

	    	$param = $msgQryFields = [];

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
	    				$msgQryFields[$segment][] = "`{$column}` = :{$name}";
	    			}
	    		}
	    	}

	    	//
	    	// recipients segment
	    	//
	    	if( is_array($META->message) && ( $recipients = $META->message['recipients'] ?? null) )
	    	{
	    		$param['recipients'] = [];

	    		foreach( $recipients as $key => $recipient )
	    		{
	    			$param['recipients'][$key] =
	    			[
	    				'message' => $this->getId(),
	    				'recipient' => $recipient->getId(),
	    				'deleted' => $recipient->isDeleted()
	    			];
	    		}
	    	}

	    	//
	    	// save the changes
	    	//
	    	$iDb = $this->_config->getDbAdapter();
	    	$dbTransaction = $iDb->beginTransaction();

	    	if( $param['message'] ?? null )
	    	{
	    		$param['message']['uid'] = $this->getId();
	    		$param['message']['created_by'] = $this->getSender();
	    		$msgQryFragment = implode(", ", $msgQryFields['message']);

				$iDb->query
				("
					UPDATE `event` SET {$msgQryFragment}
					WHERE `uid` = :uid AND `created_by` = :created_by",

					$param["message"]
				);

				$this->_propertySegment['sent_message'] = false;
	    	}

	    	if( $param['recipients'] ?? null )
	    	{
	    		foreach( $param['recipients'] as $recipientParam )
	    		{
	    			$iDb->query
	    			("
	    				INSERT INTO `message_recipients`( recipient_fk, message_fk, deleted )
	    				VALUES( :recipient, :message, :deleted )
	    				ON DUPLICATE KEY UPDATE `deleted` = VALUES(`deleted`)",

	    				$recipientParam
	    			);
	    		}
	    	}

	    	$dbTransaction && $iDb->commit();

	    	return true;
		}
	}

?>