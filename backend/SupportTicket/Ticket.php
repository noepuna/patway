<?php

/**
 * ...
 *
 * @category	App\SupportTicket
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\SupportTicket;

	use App\IConfig;
	use App\SupportTicket\TicketMeta;
	use App\SupportTicket\SupportTicketStatusMeta;





	class Ticket implements \App\SupportTicket\ITicket
	{
		use \Core\Util\TUtilOps,
			\App\SupportTicket\TTicketOps;

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
	    	'ticket' =>
	    	[
				'id' => null,
				'category' => null,
				'description' => null,
				'severity' => null,
				'status' => SupportTicketStatusMeta::ACTIVE,
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
        private $_propertySegment = [ 'ticket' => false ];

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
		public function __construct( IConfig $CONFIG, TicketMeta $META )
		{
			$t = $META->ticket;

			$createTicketRequirements = [ "category", "description", "severity", "created_by" ];

			if( $META->require("crud_method", ["ticket", ...$createTicketRequirements]) && TicketMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['ticket'] = 
				[
					'category' => $t['category'],
					'description' => $t['description'],
					'severity' => $t['severity'],
			        'created_by' => $t['created_by']->getId(),
			        'date_created' => $CONFIG->getCurrentTime()

				] + $this->_prop['ticket'];
			}
			else if( $META->require("crud_method", ["ticket", "id"]) && TicketMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['ticket'] =
				[
					'id' => $t['id']

				] + $this->_prop['ticket'];
			}
			else if( $META->require("crud_method", ["ticket", "id"]) && TicketMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop['ticket'] =
				[
					'id' => $t['id']

				] + $this->_prop['ticket'];
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
		public static function createInstance( IConfig $CONFIG, TicketMeta $META )
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
	    	return $this->_prop['ticket']['id'] ?? null;
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getCategory() : int
        {
            $this->_requireTicketSegment();

            return $this->_prop['ticket']['category'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDescription() : string
        {
            $this->_requireTicketSegment();

            return $this->_prop['ticket']['description'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getSeverity() : int
        {
            $this->_requireTicketSegment();

            return $this->_prop['ticket']['severity'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getStatus() : int
        {
            $this->_requireTicketSegment();

            return $this->_prop['ticket']['status'];
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
            $this->_requireTicketSegment();

            return $this->_prop['ticket']['created_by'];
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
            $this->_requireTicketSegment();

            return $this->_prop['ticket']['date_created'];
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
            $this->_requireTicketSegment();

            return $this->_prop['ticket']['deleted'];
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
        private function _requireTicketSegment()
        {
            if( !$this->_propertySegment['ticket'] && $this->getId() )
            {
                $t = self::t_ticketOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['ticket'] =
                [
					'id' => $t['id'],
					'category' => $t['category'],
					'description' => $t['description'],
					'severity' => $t['severity'],
					'status' => $t['status'],
					'created_by' => $t['created_by'],
					'date_created' => $t['date_created'],
					'deleted' => $t['deleted']

                ] + $this->_prop['ticket'];

                $this->_propertySegment['ticket'] = true;
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
			 * require additional ticket properties
			 */
			$ticketMeta =
			[
                'crud_method' => $this->_prop['crud_method'],
				'account' =>
				[
					'status' => $this->getStatus(),
					'date_created' => $this->getDateCreated(),
					'deleted' => $this->isDeleted(),
				]
			];

			$iTickeMeta = TicketMeta::createInstance($iConfig, $ticketMeta);

			if( $iTickeMeta instanceof TicketMeta )
			{
				$param['ticket'] =
				[
					'category_fk' => $this->getCategory(),
					'description' => $this->getDescription(),
					'severity_fk' => $this->getSeverity(),
					'status_fk' => $this->getStatus(),
					'created_by' => $this->getCreatedBy(),
					'date_created' => $this->getDateCreated(),
					'deleted' => $this->isDeleted() ? 1 : 0
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
				INSERT INTO `support_ticket`
				(`category_fk`, `description`, `severity_fk`, `status_fk`, `created_by`, `date_created`, `deleted`)
				VALUES
				(:category_fk, :description, :severity_fk, :status_fk, :created_by, :date_created, :deleted)",

				$param['ticket']
			);

			$this->_prop['ticket']['id'] = $iDb->lastInsertId();

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
		public function update( TicketMeta $META ) : ?bool
		{
			//
			// requiring crud_method, id and created_by is essential in validating other ticket properties
			//
			$updateRequirements =
			[
				"crud_method",
				[
					"ticket", "id", "created_by"
				]
			];

			//
			// crud_method must be update
			// id must be equals the value during the class construction
			//
			if( $META->require(...$updateRequirements) && $META->crud_method === TicketMeta::CRUD_METHOD_UPDATE )
			{
				$ticket = $META->ticket;
				$ticketId = $ticket['id'] ?? null;

				if( $this->getId() !== $ticketId )
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
	    	$allowedProperties['ticket'] =
	    	[
	    		[ "category_fk", "category" ],
	    		[ "description", "description" ],
	    		[ "severity_fk", "severity" ],
	    		[ "status_fk", "status" ],
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

	    	if( $param['ticket'] ?? null )
	    	{
	    		$param['ticket']['uid'] = $this->getId();
	    		$param['ticket']['created_by'] = $this->getCreatedBy();
	    		$ticketSetQry = implode(", ", $fieldSetQry['ticket']);

				$iDb->query
				("
					UPDATE `support_ticket` SET {$ticketSetQry}
					WHERE `uid` = :uid AND `created_by` = :created_by",

					$param["ticket"]
				);

				$this->_propertySegment['ticket'] = false;
	    	}

	    	$dbTransaction && $iDb->commit();

	    	return true;
		}
	}

?>