<?php

/**
 * ...
 *
 * @category	App\Account\Member
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Account\Member;

	use Core\Datatype\Undefined;
	use App\IConfig;
	use App\Account\Member\MemberMeta;

	class Member extends \App\Account\Account implements \App\Account\Member\IMember
	{
		use \App\Account\TAccountBillingOps;

        /**
         * ...
         *
         * ...
         *
         * @var Array<any>
         * @access private
         */
        private $_prop =
        [
            'crud_method' => null,
        	'account' =>
        	[
        		'auth' => null,
        		'id' => null
        	],
	    	'basic' =>
	    	[
	        	'firstname' => null,
	        	'lastname' => null,
	            'middlename' => null,
	            'email' => null,
	            'contact_number' => null,
	            'location_address' => null,
	            'date_joined' => null,
	    	],
            'billing' =>
            [
	        	'firstname' => null,
	        	'lastname' => null,
	            'middlename' => null,
	            'email' => null,
	            'contact_number' => null,
	            'address' => null
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
        private $_propertySegment = [ 'billing' => false ];

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
		public function __construct( IConfig $CONFIG, \App\Account\AccountMeta $META )
		{
			$d = $META->account;
			$b = is_a($META->billing, Undefined::t_UtilOps_classWithBackSlash()) ?[] :$META->billing;

	    	if( $META->require("crud_method", ["account", "auth"]) && MemberMeta::CRUD_METHOD_CREATE === $META->crud_method )
	    	{
                $this->_prop =
                [
                    'crud_method' => $META->crud_method

                ] + $this->_prop;

                $this->_prop['account'] =
                [
                    'auth' => $d['auth']

                ] + $this->_prop['account'];

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

				$this->_prop['billing'] =
				[
                    'firstname' => @$b['firstname'],
                    'lastname' => @$b['lastname'],
                    'middlename' => @$b['middlename'],
                    'email' => @$b['email'],
                    'contact_number' => @$b['tel_num'],
                    'address' => @$b['address']

				] + $this->_prop['billing'];
	    	}
	        else if( $META->require("crud_method", ["account", "auth"]) && MemberMeta::CRUD_METHOD_READ === $META->crud_method )
	        {
                $this->_prop =
                [
                    'crud_method' => $META->crud_method

                ] + $this->_prop;

                $this->_prop['account'] =
                [
                    'auth' => $d['auth'],
                    'id' => $d['auth']->getId()

                ] + $this->_prop['account'];
	        }
	        else if( $META->require("crud_method", ["account", "auth"]) && MemberMeta::CRUD_METHOD_UPDATE === $META->crud_method )
	        {
                $this->_prop =
                [
                    'crud_method' => $META->crud_method

                ] + $this->_prop;

                $this->_prop['account'] =
                [
                    'auth' => $d['auth'],
                    'id' => $d['auth']->getId()

                ] + $this->_prop['account'];
	        }
	    	else
	    	{
	    		throw new \Exception("Insufficient meta", 1);
	    	}

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
		 * public static function createInstance( IConfig $CONFIG, MerchantMeta $META )
		 * this is unsupported for now.
		 * see https://wiki.php.net/rfc/return_types#future_work
		 */
		public static function createInstance( IConfig $CONFIG, \App\Account\AccountMeta $META )
		{
			assert($META instanceof MemberMeta);

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
         *  ...
         *
         * @access private
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        private function _constructAuthToken( IConfig $CONFIG, MemberMeta $META )
        {

        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingFirstname() : string
        {
            $this->_requireBillingSegment();

            return $this->_prop['billing']['firstname'] ?$this->_prop['billing']['firstname'] :$this->getFirstname();
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingMiddlename() : ?string
        {
            $this->_requireBillingSegment();

            return $this->_prop['billing']['middlename'] ?$this->_prop['billing']['middlename'] :$this->getMiddlename();
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingLastname() : string
        {
            $this->_requireBillingSegment();

            return $this->_prop['billing']['lastname'] ?$this->_prop['billing']['lastname'] :$this->getLastname();
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingEmail() : string
        {
            $this->_requireBillingSegment();

            return $this->_prop['billing']['email'] ?$this->_prop['billing']['email'] :$this->getEmail();
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingAddress() : string
        {
            $this->_requireBillingSegment();

            return $this->_prop['billing']['address'] ?$this->_prop['billing']['address'] :$this->getLocationAddress();
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingContactNumber() : string
        {
            $this->_requireBillingSegment();

            return $this->_prop['billing']['contact_number'] ?$this->_prop['billing']['contact_number'] :$this->getContactNumber();
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
        private function _requireBillingSegment()
        {
            if( $this->_prop['crud_method'] !== MemberMeta::CRUD_METHOD_CREATE && !$this->_propertySegment['billing'] && $this->getId() )
            {
                $d = self::t_AccountBillingOps_getInfoById($this->_config, $this->getId()) ?? [];

                $this->_prop['billing'] =
                [
                    'firstname' => $d['firstname'] ?? null,
                    'lastname' => $d['lastname'] ?? null,
                    'middlename' => $d['middlename'] ?? null,
                    'email' => $d['email'] ?? null,
                    'address' => $d['address'] ?? null,
                    'contact_number' => $d['contact_number'] ?? null

                ] + $this->_prop['billing'];

                $this->_propertySegment['billing'] = true;
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
        	return in_array($this->_prop['crud_method'], [MemberMeta::CRUD_METHOD_READ, MemberMeta::CRUD_METHOD_UPDATE]);
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
			 * save the data
			 */
            $db = $iConfig->getDbAdapter();
            $existingTransaction = $db->beginTransaction();

			if( !($authId = Parent::create()) )
			{
				return null;
			}

			$db->query
			("
				INSERT INTO `account_billing_information`
				(`auth_fk`, `firstname`, `lastname`, `middlename`, `email`, `tel_num`, `mobile_num`, `address`)
				VALUES
				(:auth_fk, :firstname, :lastname, :middlename, :email, :tel_num, :mobile_num, :address)",

				[
					'auth_fk' => $authId,
					'firstname' => $this->getBillingFirstname(),
					'lastname' => $this->getBillingLastname(),
					'middlename' => $this->getBillingMiddlename(),
					'email' => $this->getBillingEmail(),
					'tel_num' => $this->getBillingContactNumber(),
					'mobile_num' => $this->getBillingContactNumber(),
					'address' => $this->getBillingAddress()
				]
			);

            $existingTransaction && $db->commit();

            $this->_prop['id'] = $authId;

            return $authId;
		}





        public function update( Array $META ) : ?Array
        {
            $allowedProperties =
            [
                'billing' =>
                [
                    ["firstname", "firstname"], ["lastname", "lastname"], ["middlename", "middlename"],
                    ["email", "email"], ["address", "address"], ["tel_num", "contact_number"]
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

            if( $param )
            {
                $META =
                [
                    'crud_method' => MemberMeta::CRUD_METHOD_UPDATE,
                    'account' =>
                    [
                        'auth' => $this->getAuth()

                    ]
                ];

                $iMeta = MemberMeta::createInstance($this->_config, $META);

                if( false === is_a($iMeta, MemberMeta::t_UtilOps_classWithBackSlash()) )
                {
                    return $iMeta;
                }

                $db = $this->_config->getDbAdapter();
                $existingTransaction = $db->beginTransaction();

                if( array_key_exists("billing", $param) )
                {
                    $param["billing"]['auth_fk'] = $this->getId();
                    $billingSetQry = implode(", ", $fieldSetQry['billing']);

                    $db->query("UPDATE `account_billing_information` SET {$billingSetQry} WHERE `auth_fk` = :auth_fk", $param["billing"]);
                    $this->_propertySegment['billing'] = false;
                }

                $existingTransaction && $db->commit();
            }

            return Parent::update($META);
        }
	}
?>