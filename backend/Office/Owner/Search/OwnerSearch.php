<?php

	/**
	 * business logic for Office Owner Search
	 *
	 * @category	App\Offce\Owner\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Office\Owner\Search;

	use App\IConfig;
	use App\Office\Owner\Search\OwnerSearchMeta;





	Class OwnerSearch extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	    	SELECT ACC_BIO.auth_fk AS `id`, ACC_BIO.firstname, ACC_BIO.middlename, ACC_BIO.lastname, ACC_BIO.email,
	    	ACC_BIO.address, ACC_BIO.tel_num,
	    	ACC_BIO.date_joined,
	    	AUTH.status_fk AS `status`, AUTH.disabled,
	    	OFF.name AS `off_name`, OFF.enabled AS `off_enabled`, COUNT(OFF_STAFF.created_by) AS `total_users`
	    	FROM `auth` AUTH
	    	INNER JOIN `auth_previleges` AUTH_P ON AUTH_P.auth_fk = AUTH.uid AND AUTH_P.previlege_fk = 4
	    	INNER JOIN `account_basic_information` ACC_BIO ON ACC_BIO.auth_fk = AUTH.uid
	    	INNER JOIN `account_billing_information` ACC_BILL ON ACC_BILL.auth_fk = AUTH.uid
	    	INNER JOIN `office` OFF ON OFF.auth_fk = AUTH.uid
	    	LEFT JOIN `office_staff` OFF_STAFF ON OFF_STAFF.created_by = AUTH.uid
	    ";//should join auth with previleges with office previlege on "ON" clause





		/**
		 *	...
		 *
		 * @access public
		 * @param ...
		 * @return ...
		 * @throws \Exception Invalid token.
		 * @throws \Exception Unsupported cipher method.
		 * @throws \Exception Insufficient Metadata.
		 * @since Method available since Beta 1.0.0
		 *
		 * ...
		 */
		public function __construct( Iconfig $CONFIG, OwnerSearchMeta $META )
		{
			Parent::__construct($CONFIG, $META);
		}





		/**
		 *	...
		 *
		 *	@access private
		 *	@param ...
		 *	@return ...
		 *	@since Method available since Beta 1.0.0
		 *
		 *	...
		 *	...
		 *	...
		 *	...
		 */
		public static function createInstance( IConfig $CONFIG, \Core\API\PaginationMeta $META )
		{
			assert($META instanceof OwnerSearchMeta);

			try
			{
				return new self($CONFIG, $META);
			}
			catch( \Exception $e)
			{
				return false;
			}
		}
	}

?>