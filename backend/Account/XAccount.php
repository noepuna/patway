<?php

    /**
     * Abstract class for Accounts
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

    use Resource\ReturnMsg;
    use App\Account\AccountMeta;
    use App\Account\AccountAddressMeta;
    use App\Account\AccountContactMeta;

    Abstract Class XAccount implements \App\Account\IAccount
    {
        use \Core\Util\TUtilOps, \App\Account\TAccountOps, \App\Account\Task\TTaskOps;

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
        	'id' => null,
        	'firstname' => null,
        	'lastname' => null,
            'middlename' => null,
            'email' => null,
            'contacts' => [],
            'addresses' => [],
            'contact_number' => null,
            'location_address' => null,
            'date_joined' => null,
            'tasks' => []
        ];

        /**
         * ...
         *
         * ...
         *
         * @var App\IConfig;
         * @access private
         */
        private $_config;

        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         *  ...
         *  ...
         *  ...
         */
        public function __construct( \App\IConfig $CONFIG, AccountMeta $META )
        {
            $d = $META->account;
            $additionalParam = [];

            if( $META->require("crud_method") && AccountMeta::CRUD_METHOD_CREATE === $META->crud_method )
            {
                $additionalParam =
                [
                    'firstname' => @$d['firstname'],
                    'lastname' => @$d['lastname'],
                    'middlename' => @$d['middlename'],
                    'email' => @$d['email'],
                    'contacts' =>
                    [
                        AccountContactMeta::TYPE_PRIMARY_EMAIL => @$d['email'],
                        AccountContactMeta::TYPE_TELEPHONE => @$d['tel_num'],
                        AccountContactMeta::TYPE_MOBILE => @$d['mobile_num']
                    ],
                    'addresses' =>
                    [
                        AccountAddressMeta::TYPE_LOCATION => @$d['location_address']
                    ],
                    'contact_number' => @$d['tel_num'],
                    'location_address' => @$d['location_address'],
                    'date_joined' => $CONFIG->getCurrentTime(),
                    'task' => $META->tasks ?? []
                ];
            }
            else if( $META->require("crud_method", ["account", "auth"]) && AccountMeta::CRUD_METHOD_READ === $META->crud_method )
            {
                $this->_prop = [ 'auth' => $META->account['auth'] ] + $this->_prop;
            }
        	else
        	{
        		throw new \Exception("Insufficient meta", 1);
        	}

            $this->_config = $CONFIG;
            $this->_prop = array_merge($this->_prop, $additionalParam);
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
        	return $this->_prop['id'];
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
            return $this->_prop['firstname'];
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
            return $this->_prop['lastname'];
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
            return $this->_prop['middlename'];
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
            return $this->_prop['email'];
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
            return $this->_prop['location_address'];
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
            return $this->_prop['contact_number'];
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
            return $this->_prop['date_joined'];
        }

        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function taskAvailable( string... $ACTIONS ) : bool
        {
            return false;
        }
    }

?>