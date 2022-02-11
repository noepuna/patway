<?php

	/**
	 * business logic for Office Staff Search
	 *
	 * @category	App\Offce\Staff\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Office\Staff\Search;

	use App\IConfig;
	use App\Office\Staff\Search\StaffSearchMeta;





	Class StaffSearch extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	    	SELECT ACC_BIO.auth_fk AS `id`, ACC_BIO.firstname, ACC_BIO.middlename, ACC_BIO.lastname, ACC_BIO.email, ACC_BIO.address,
	    	ACC_BIO.date_joined,
	    	AUTH.status_fk AS `status`, AUTH.disabled,
	    	OFC_STF.deleted AS `deleted`
	    	FROM `auth` AUTH
	    	INNER JOIN `account_basic_information` ACC_BIO ON ACC_BIO.auth_fk = AUTH.uid
	    	INNER JOIN `office_staff` OFC_STF ON OFC_STF.auth_fk = AUTH.uid
	    ";





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
		public function __construct( Iconfig $CONFIG, StaffSearchMeta $META )
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
			assert($META instanceof StaffSearchMeta);

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