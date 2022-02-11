<?php

	/**
	 * business logic for Environment Health & Safety Search
	 *
	 * @category	App\EnvironmentHealthSafety\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\EnvironmentHealthSafety\Search;

	use App\IConfig;
	use App\EnvironmentHealthSafety\Search\EHSSearchMeta;





	Class EHSSearch extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	   		SELECT EHS.uid AS `id`, EHS.name, EHS.date_created, EHS.enabled, EHS.deleted,
	   		ICON.uid AS `icon_id`, ICON.url_path AS `icon_url`,
	   		EHS.created_by AS owner_id, CONCAT(OWNER.firstname, ' ', OWNER.lastname) AS owner_name
	    	FROM `environment_health_safety` EHS
	    	LEFT JOIN `app_files` ICON ON ICON.uid = EHS.icon
	    	LEFT JOIN `office_staff` OFC ON OFC.created_by = EHS.created_by
	    	LEFT JOIN `account_basic_information` OWNER ON OWNER.auth_fk = EHS.created_by

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
		public function __construct( Iconfig $CONFIG, EHSSearchMeta $META )
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
			assert($META instanceof EHSSearchMeta);

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