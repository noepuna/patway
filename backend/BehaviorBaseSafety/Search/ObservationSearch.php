<?php

	/**
	 * business logic for Behavior Base Safety Observation Search
	 *
	 * @category	App\BehaviorBaseSafety\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\BehaviorBaseSafety\Search;

	use App\IConfig;
	use App\BehaviorBaseSafety\Search\ObservationSearchMeta;





	Class ObservationSearch extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
			SELECT BBS_O.uid AS `id`, BBS_O.observer, BBS_O.supervisor, BBS_O.notes, BBS_O.recommendation, BBS_O.action_taken,
			BBS_O.feedback_to_coworkers, BBS_O.date_created, BBS_O.deleted,
			BBS_O.created_by AS owner_id, CONCAT(OWNER.firstname, ' ', OWNER.lastname) AS owner_name
	    	FROM `bbs_observation` BBS_O
	    	LEFT JOIN `account_basic_information` OWNER ON OWNER.auth_fk = BBS_O.created_by
	    	LEFT JOIN `office_staff` OFC_STAFF_JUNCTION ON BBS_O.created_by = OFC_STAFF_JUNCTION.auth_fk
	    	LEFT JOIN `office_staff` OFC_STAFF ON OFC_STAFF_JUNCTION.created_by = OFC_STAFF.created_by
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
		public function __construct( Iconfig $CONFIG, ObservationSearchMeta $META )
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
			assert($META instanceof ObservationSearchMeta);

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