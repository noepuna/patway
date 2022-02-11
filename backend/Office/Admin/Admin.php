<?php

	/**
	 * Admin Class
	 *
	 * @category	App\Office\Admin
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	 
	namespace App\Office\Admin;

	use App\IConfig;
	use App\Office\Admin\AdminMeta;





	class Admin extends \App\Account\Account implements \App\Office\Admin\IAdmin
	{
		use \App\Office\Admin\TAdminOps;

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
			'admin' => []
		];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'admin' => false ];

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Resource\IConfig;
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
		public function __construct( IConfig $CONFIG, AdminMeta $META )
		{
	    	if( $META->require("crud_method", ["admin", "owner"]) && AdminMeta::CRUD_METHOD_CREATE === $META->crud_method )
	    	{
	    		$this->_prop['admin'] =
	    		[
	    			'owner' => $META->admin['owner']->getId()

	    		] + $this->_prop['admin'];
	    	}
	    	else if( $META->require("crud_method") && AdminMeta::CRUD_METHOD_READ === $META->crud_method )
	    	{
	    		//noop
	    	}
	    	else if( $META->require("crud_method") && AdminMeta::CRUD_METHOD_UPDATE === $META->crud_method )
	    	{
	    		//noop
	    	}
	    	else
	    	{
	    		throw new \Exception("Insufficient meta", 1);
	    	}

	    	$this->_config = $CONFIG;
	    	$this->_prop['crud_method'] = $META->crud_method;

			Parent::__construct($CONFIG, $META);
		}





        /**
         * ...
         *
    	 * @access public
    	 * @param
    	 * @return
    	 * @since Method available since Beta 1.0.0
         */
        public function getOwner() : string
        {
        	$this->_requireAdminSegment();

        	return $this->_prop['admin']['owner'];
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
		 * public static function createInstance( IConfig $CONFIG, AdminMeta $META )
		 * this is unsupported for now.
		 * see https://wiki.php.net/rfc/return_types#future_work
		 */
		public static function createInstance( IConfig $CONFIG, \App\Account\AccountMeta $META )
		{
			assert($META instanceof AdminMeta);

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
	    public function updateUserAccountStatus()
	    {
	    	if( $META->require(["admin", "account_id"]) )
	    	{
	    		$db = $this->_config->getDbAdapter();

	    		$param = [ 'id' => $META->admin['account_id'] ];

	    		$isSingleTransaction = $db->beginTransaction();

	    		if( array_key_exists("account_status", $META->admin) )
	    		{
	    			$basicParam = $param + [ 'status' => $META->admin['account_status'] ];

	    			$db->query
	    			("
	    				UPDATE `account_basic_information`
	    				SET `status_fk` = :status
	    				WHERE `auth_fk` = :id",

	    				$basicParam
	    			);
	    		}

	    		if( array_key_exists("account_enabled", $META->admin) )
	    		{
	    			$authParam = $param + [ 'enabled' => $META->admin['account_enabled'] ];

	    			$db->query("UPDATE `auth` SET `enabled` = :enabled WHERE `uid` = :id", $authParam);
	    		}

	    		$isSingleTransaction && $db->commit();

	    		return ReturnMsg::success(['update_account_status' => true]);
	    	}
	    	else
	    	{
	    		$errMsg = "insufficient meta for " . self::t_UtilOps_classWithBackslash() . "->updateUserAccountStatus";

	    		return ReturnMsg::failure(['update_account_status' => $errMsg]);
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
        private function _requireAdminSegment()
        {
            if( !$this->_propertySegment['admin'] && $this->getId() && $this->isCRUDReadWrite() )
            {
                $d = self::t_AdminOps_getInfoById($this->_config, $this->getId());

                $this->_prop['admin'] =
                [
                    'owner' => $d['created_by']

                ] + $this->_prop['admin'];

                $this->_propertySegment['admin'] = true;
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
		public function create() :? string
		{
			$db = $this->_config->getDbAdapter();
			$existingTransaction = $db->beginTransaction();

			$accountId = Parent::create();

			if( true === is_null($accountId) )
			{
				$existingTransaction && $db->rollback();
			}
			else
			{
				$param =
				[
					'auth_fk' => $accountId,
					'created_by' => $this->getOwner(),
					'office_fk' => $this->getOwner(),
					'deleted' => 0
				];

				$db->query
				("
					INSERT INTO `office_staff`(`auth_fk`, `created_by`, `office_fk`, `deleted`)
					VALUES(:auth_fk, :created_by, :office_fk, :deleted)",

					$param
				);
			}

			$existingTransaction && $db->commit();

			return $accountId;
		}
	}

?>