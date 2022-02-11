<?php

/**
 * ...
 *
 * @category	App\Account
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Account;

	use App\IConfig;
	use Resource\API\Pagination;
	use App\Account\AccountMeta;

	class Account extends \App\Account\XAccount implements \App\Account\IAccount
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
	    	'basic' => [],
	    	'task' 	=> []
	    ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'basic' => false ];

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
		public function __construct( IConfig $CONFIG, AccountMeta $META )
		{
			$d = $META->account;

			if( $META->require("crud_method", ["account", "auth"]) && AccountMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
				$this->_prop =
				[
					'crud_method' => $META->crud_method,
					'auth' => $d['auth']

				] + $this->_prop;

	    		$this->_prop['basic'] = 
				[
			        'firstname' => @$d['firstname'],
			        'lastname' => @$d['lastname'],
			        'middlename' => @$d['middlename'],
			        'email' => @$d['email'],
			        'contact_number' => @$d['tel_num'],
			        'location_address' => @$d['location_address'],
			        'date_joined' => $CONFIG->getCurrentTime()

				] + $this->_prop['basic'];

				if( is_array($META->task) )
				{
					$this->_prop['task'] = $META->task + $this->_prop['task'];
				}
			}
			else if( $META->require("crud_method", ["account", "auth"]) && AccountMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop =
				[
					'crud_method' => $META->crud_method,
					'auth' => $d['auth'],
					'id' => $d['auth']->getId()

				] + $this->_prop;
			}
			else if( $META->require("crud_method", ["account", "auth"]) && AccountMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop =
				[
					'crud_method' => $META->crud_method,
					'auth' => $d['auth'],
					'id' => $d['auth']->getId()

				] + $this->_prop;
			}
			else
			{
				throw new \Exception("Invalid meta", 1);
			}

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
		public static function createInstance( IConfig $CONFIG, AccountMeta $META )
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
        public function getAuth() : \App\Auth\IAuth
        {
            return $this->_prop['auth'];
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
	    	return $this->_prop['id'] ?? null;
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getFirstName() : ?string
        {
            $this->_requireBasicSegment();

            return $this->_prop['basic']['firstname'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getLastName() : ?string
        {
            $this->_requireBasicSegment();

            return $this->_prop['basic']['lastname'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getMiddleName() : ?string
        {
            $this->_requireBasicSegment();

            return $this->_prop['basic']['middlename'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getEmail() : ?string
        {
            $this->_requireBasicSegment();

            return $this->_prop['basic']['email'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getLocationAddress() : ?string
        {
            $this->_requireBasicSegment();

            return $this->_prop['basic']['location_address'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getContactNumber() : ?string
        {
            $this->_requireBasicSegment();

            return $this->_prop['basic']['contact_number'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDateJoined() : int
        {
            $this->_requireBasicSegment();        

            return $this->_prop['basic']['date_joined'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function taskAvailable( string... $TASKS ) : bool
        {
        	if( $this->getId() )
        	{
        		return $this->t_TaskOps_isAvailableByAccount($this->_config, $this->getId(), ...$TASKS);
        	}

            return false;
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
        private function _requireBasicSegment()
        {
            if( !$this->_propertySegment['basic'] && $this->getId() )
            {
                $d = self::t_AccountOps_getInfoById($this->_config, $this->getId());

                $this->_prop['basic'] =
                [
                    'firstname' => $d['firstname'],
                    'lastname' => $d['lastname'],
                    'middlename' => $d['middlename'],
                    'email' => $d['email'],
                    'location_address' => $d['address'],
                    'contact_number' => $d['contact_number'],
                    'date_joined' => $d['date_joined']

                ] + $this->_prop['basic'];

                $this->_propertySegment['basic'] = true;
            }
        }





        /**
         *  ...
         *
         * @access protected
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        protected function isCRUDReadWrite() : bool
        {
        	return in_array($this->_prop['crud_method'], [AccountMeta::CRUD_METHOD_READ, AccountMeta::CRUD_METHOD_UPDATE]);
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
			 * require specific account properties
			 */
			$accountMeta =
			[
                'crud_method' => $this->_prop['crud_method'],
				'account' =>
				[
					'auth' => $this->_prop['auth'],
					'firstname' => $this->getFirstname(),
					'lastname' => $this->getLastname(),
					'email' => $this->getEmail(),
					'location_address' => $this->getLocationAddress(),
					'date_joined' => $this->getDateJoined(),
				]
			];

			$accountMeta = AccountMeta::searchAndRemove($accountMeta, null);
			$iAccountMeta = AccountMeta::createInstance($iConfig, $accountMeta);

			if( $iAccountMeta instanceof AccountMeta )
			{
				/*
				 * some properties can be null, so using the require method will make sure that all the requirements are present
				 */
				$requirements =	[ "crud_method", ["account", "auth", "firstname", "lastname", "email", "location_address", "date_joined"] ];

				if( false == $iAccountMeta->require(...$requirements) || AccountMeta::CRUD_METHOD_CREATE !== $iAccountMeta->crud_method )
				{
					return null;
				}
			}
			else
			{
				return null;
			}

			//
			// save the data
			//
			$db = $iConfig->getDbAdapter();
			$dbTransaction = $db->beginTransaction();

			$iAuth = $iAccountMeta->account['auth'];

			if( !$iAuth->getId() && !$iAuth->create() )
			{
				$dbTransaction && $db->rollback();

				return null;
			}

			$authId = $iAuth->getId();

			//
			// creating multiple subClasses for an account will duplicate the basic record, so we sill skip it, if that happens
			//
			if( !$this->t_accountOps_exists($iConfig, $authId) )
			{
				$db->query
				("
					INSERT INTO `account_basic_information`
					(`auth_fk`, `firstname`, `lastname`, `middlename`, `email`, `tel_num`, `mobile_num`, `address`, `date_joined`)
					VALUES
					(:auth_fk, :firstname, :lastname, :middlename, :email, :tel_num, :mobile_num, :address, :date_joined)",

					[
						'auth_fk' => $authId,
			 			'firstname' => $this->getFirstname(),
			 			'lastname' => $this->getLastname(),
			 			'middlename' => $this->getMiddlename(),
						'email' => $this->getEmail(),
						'tel_num' => $this->getContactNumber(),
						'mobile_num' => $this->getContactNumber(),
						'address' => $this->getLocationAddress(),
						'date_joined' => $this->getDateJoined()
					]
				);
			}

			//
			// save the account tasks assigned for this account
			//
			if( $this->_prop['task'] )
			{
				$param = $valueStr = [];

				foreach( $this->_prop['task'] as $key => $task )
				{
					$authKey = "auth" . $key;
					$taskKey = "task" . $key;
					$param[$authKey] = $authId;
					$param[$taskKey] = $task->getId();
					$valueStr[] = "(:{$authKey}, :{$taskKey})";
				}

				$valueStr = implode(", ", $valueStr);

				$db->query("INSERT INTO `account_tasks`(`auth_fk`, `task_fk`) VALUES {$valueStr}", $param);
			}

			$dbTransaction && $db->commit();

			$this->_prop['id'] = $authId;

			return $authId;
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
		public function update( Array $META ) : ?Array
		{
	    	$META =
	    	[
	    		'crud_method' => AccountMeta::CRUD_METHOD_UPDATE,
	    		'task' => $META['task'] ?? [],
	    		'account' =>
	    		[
	    			'auth' => $this->_prop['auth']

	    		] + ($META['account'] ?? [])
	    	];

	    	$iMeta = AccountMeta::createInstance($this->_config, $META);

	    	if( false === is_a($iMeta, AccountMeta::t_UtilOps_classWithBackSlash()) )
	    	{
	    		return $iMeta;
	    	}

	    	$allowedProperties =
	    	[
	    		'account' =>
	    		[
	    			["firstname", "firstname"], ["lastname", "lastname"], ["middlename", "middlename"],
	    			["email", "email"], ["address", "location_address"], ["tel_num", "contact_number"]
	    		]
	    	];

	    	$param = $fieldSetQry = [];

	    	foreach($allowedProperties as $segment => $props)
	    	{
	    		if( !($segmentProp = $META[$segment] ?? null) )
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

	    	$db = $this->_config->getDbAdapter();
	    	$existingTransaction = $db->beginTransaction();

	    	if( is_array($META['task'] ?? null) && count($META['task']) )
	    	{
	    		foreach( $META['task'] as $key => $iTask )
	    		{
	    			$authIdKey = "auth" . $key;
	    			$taskIdKey = "task" . $key;
	    			$taskStatusKey = "status" . $key;
	    			$param['task'][$authIdKey] = $this->getId();
	    			$param['task'][$taskIdKey] = $iTask->getId();
	    			$param['task'][$taskStatusKey] = $iTask->disabled() ?1 :0;
	    			$taskValStr[] = "(:{$authIdKey}, :{$taskIdKey}, :{$taskStatusKey})";
	    		}

	    		$db->query
	    		("
	    			INSERT INTO `account_tasks`(`auth_fk`, `task_fk`, `disabled`)
	    			VALUES" . implode(", ", $taskValStr) . " ON DUPLICATE KEY UPDATE `disabled` = VALUES(`disabled`)",

	    			$param['task']
	    		);
	    	}

	    	if( $param['account'] ?? null )
	    	{
		    	if( array_key_exists("account", $param) )
		    	{
		    		$param["account"]['auth_fk'] = $this->getId();
		    		$accountSetQry = implode(", ", $fieldSetQry['account']);

					$db->query("UPDATE `account_basic_information` SET {$accountSetQry} WHERE `auth_fk` = :auth_fk", $param["account"]);
					$this->_propertySegment['basic'] = false;
		    	}
	    	}

	    	$existingTransaction && $db->commit();

	    	return null;
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
		public static function search( IConfig $CONFIG, AccountSearchMeta $META ) : ReturnMsg
		{
			//todo: create this
		}
	}

?>