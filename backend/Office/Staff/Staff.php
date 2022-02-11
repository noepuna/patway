<?php

	/**
	 * Staff Class
	 *
	 * @category	App\Office\Staff
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */

	namespace App\Office\Staff;

	use App\IConfig;
	use App\Office\Staff\StaffMeta;

	class Staff extends \App\Account\Account implements \App\Office\Staff\IStaff
	{
		use \App\Office\Staff\TStaffOps,
			\App\Office\Staff\TDepartmentEntryOps;





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
			'department_entries' => [],
			'staff' =>
			[
				'admin' => null,
				'office' => null
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
        private $_propertySegment = [ 'staff' => false, 'department_entries' => false ];





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
            'create' => [ "crud_method", [ "staff", "admin" ] ]
        ];





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
		public function __construct( IConfig $CONFIG, StaffMeta $META )
		{
			Parent::__construct($CONFIG, $META);

	    	$this->_config = $CONFIG;

	    	if( $META->require(...self::_CTOR_REQS['create']) && StaffMeta::CRUD_METHOD_CREATE === $META->crud_method )
	    	{
	    		$iAdmin = $META->staff['admin'];

	    		$this->_prop['staff'] =
	    		[
	    			'admin' => $iAdmin->getId(),
	    			'office' => $iAdmin->getOwner()

	    		] + $this->_prop['staff'];
	    	}
	    	else if( $META->require("crud_method") && StaffMeta::CRUD_METHOD_READ === $META->crud_method )
	    	{
				// noop
	    	}
	    	else if( $META->require("crud_method") && StaffMeta::CRUD_METHOD_UPDATE === $META->crud_method )
	    	{
				// noop
	    	}
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
		 * public static function createInstance( IConfig $CONFIG, StaffMeta $META )
		 * this is unsupported for now.
		 * see https://wiki.php.net/rfc/return_types#future_work
		 */
		public static function createInstance( IConfig $CONFIG, \App\Account\AccountMeta $META ) : ?self
		{
			assert($META instanceof StaffMeta);

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
        public function getAdmin() : string
        {
        	$this->_requireAdminSegment();
        	
            return $this->_prop['staff']['admin'];
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
        	$this->_requireAdminSegment();
        	
            return $this->_prop['staff']['office'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDepartments() : Array
        {
        	$this->_requireDepartmentEntriesSegment();
        	
            return $this->_prop['department_entries'];
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
        private function _requireDepartmentEntriesSegment()
        {
            if( !$this->_propertySegment['department_entries'] && $this->getId() )
            {
                $d = self::t_DepartmentEntryOps_getByAccount($this->_config, $this->getId());

                $this->_prop['department_entries'] = array_unique( array_merge( $d,  $this->_prop['department_entries'] ) );

                $this->_propertySegment['department_entries'] = true;
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
            if( !$this->_propertySegment['staff'] && $this->getId() && $this->isCRUDReadWrite() )
            {
                $d = self::t_StaffOps_getInfoById($this->_config, $this->getId());

                $this->_prop['staff'] =
                [
                    'admin' => $d['created_by'],
                    'office' => $d['office']

                ] +  $this->_prop['staff'];

                $this->_propertySegment['staff'] = true;
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
        	return Parent::isCRUDReadWrite() && in_array($this->_prop['crud_method'], [StaffMeta::CRUD_METHOD_READ, StaffMeta::CRUD_METHOD_UPDATE]);
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
					'created_by' => $this->getAdmin(),
					'office_id' => $this->getOffice(),
					'deleted' => 0
				];

				$db->query
				("
					INSERT INTO `office_staff`(`auth_fk`, `created_by`, `office_fk`, `deleted`)
					VALUES(:auth_fk, :created_by, :office_id, :deleted)",

					$param
				);
			}

			$existingTransaction && $db->commit();

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
			$iDb = $this->_config->getDbAdapter();

			$iTransaction = $iDb->beginTransaction();

			if( $errors = Parent::update($META) )
			{
				return $errors;
			}

			$iMeta = StaffMeta::createInstance( $this->_config, $META );

			if( !is_a( $iMeta, StaffMeta::t_utilOps_classWithBackslash() ) )
			{
				return $iMeta;
			}

			$staff = $iMeta->staff;

			if( is_array($staff) &&  is_array( $departmentEntries = $staff['department'] ?? null ) )
			{
				$paramDept = [];
				$valueSQLFrag = [];

				foreach( $departmentEntries as $key => $iEntry )
				{
					$staffIndex = "staff" . $key;
					$departmentIndex = "department" . $key;
					$deletedIndex = "deleted" . $key;

					$paramDept +=
					[
						$staffIndex => $this->getId(),
						$departmentIndex => $iEntry->getId(),
						$deletedIndex => $iEntry->isDeleted()
					];

					$valueSQLFrag[] = ":{$staffIndex}, :{$departmentIndex}, :{$deletedIndex}";
				}

				$valueSQLFrag = "(" . implode("), (", $valueSQLFrag) . ")";

		        $iDb->query
		        ("
		            INSERT INTO `office_department_staff`(`auth_fk`, `department_fk`, `deleted`)
		            VALUES {$valueSQLFrag}
		            ON DUPLICATE KEY UPDATE `deleted` = VALUES(`deleted`)",

		            $paramDept
		        );
			}

			$this->_propertySegment['staff'] = false;

			$iTransaction && $iDb->commit();

			return null;
		}
	}

?>