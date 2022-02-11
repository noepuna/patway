<?php

	/**
	 * Office Owner Class
	 *
	 * @category	App\Office
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */

	namespace App\Office;

	use App\IConfig;
	use App\Auth\AuthPrevilegeMeta;
	use App\Office\OwnerMeta;

	class Owner extends \App\Account\Member\Member implements \App\Office\IOwner
	{
		use \App\Office\TOwnerOps;

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
			'office' =>
			[
				'name' => null,
				'enabled' => false
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
        private $_propertySegment = [ 'office' => false ];

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
		public function __construct( IConfig $CONFIG, OwnerMeta $META )
		{
			Parent::__construct($CONFIG, $META);

	    	$this->_config = $CONFIG;

	    	if( $META->require("crud_method", ["office", "name"]) && OwnerMeta::CRUD_METHOD_CREATE === $META->crud_method )
	    	{
	    		$this->_prop['office'] =
	    		[
	    			'name' => $META->office['name']

	    		] + $this->_prop['office'];
	    	}
	    	else if( $META->require("crud_method", ["account", "auth"]) && OwnerMeta::CRUD_METHOD_READ === $META->crud_method )
	    	{
				// noop
	    	}
	    	/*else if( $META->require(["account", "crud_method", "auth"]) && OwnerMeta::CRUD_METHOD_UPDATE === $META->account['crud_method'] )
	    	{
				// noop
	    	}*/
	    	else
	    	{
	    		throw new \Exception("Insufficient meta", 1);
	    	}

    		$this->_prop['crud_method'] = $META->crud_method;
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
		 * public static function createInstance( IConfig $CONFIG, OwnerMeta $META )
		 * this is unsupported for now.
		 * see https://wiki.php.net/rfc/return_types#future_work
		 */
		public static function createInstance( IConfig $CONFIG, \App\Account\AccountMeta $META )
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
        public function getOffice() : string
        {
        	$this->_requireOfficeSegment();
        	
            return $this->_prop['office']['name'];
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
        private function _requireOfficeSegment()
        {
            if( !$this->_propertySegment['office'] && $this->getId() && $this->isCRUDReadWrite() )
            {
                $d = self::t_OwnerOps_getInfoById($this->_config, $this->getId());

                $this->_prop['office'] =
                [
                    'name' => $d['name']

                ] +  $this->_prop['office'];

                $this->_propertySegment['office'] = true;
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
        	return Parent::isCRUDReadWrite() && in_array($this->_prop['crud_method'], [OwnerMeta::CRUD_METHOD_READ, OwnerMeta::CRUD_METHOD_UPDATE]);
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
					'auth_fk' => $this->getId(),
					'name' => $this->getOffice(),
					'enabled' => OwnerMeta::ENABLED
				];

				$db->query
				("
					INSERT INTO `office`(`auth_fk`, `name`, `enabled`)
					VALUES(:auth_fk, :name, :enabled)",

					$param
				);

				$existingTransaction && $db->commit();
			}

			return $accountId;
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
		public function update( Array $META ) : ?Array
		{
			assert( $META instanceof OwnerMeta );
		}
	}

?>