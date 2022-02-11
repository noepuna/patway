<?php

	/**
	 * business logic for EHS Messaging Search
	 *
	 * @category	App\EnvironmentHealthSafety\Messaging\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\EnvironmentHealthSafety\Messaging\Search;

	use App\IConfig;
	use App\EnvironmentHealthSafety\Messaging\Search\EHSMessageSearchMeta;





	Class EHSMessageSearch extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	   		SELECT MSG.uid AS `id`, MSG.message_type_fk AS `message_type`, MSG.date_created, MSG.deleted,
	   		MSG.created_by AS `sender_id`, CONCAT(CREATED_BY.firstname, ' ', CREATED_BY.lastname) AS sender_name,
	   		EHS_MSG.title, EHS_MSG.location, EHS_MSG.description, EHS_MSG.risk_level_fk AS `risk_level`, EHS_MSG.status_fk AS `status`,
	   		EHS_MSG.date_start, EHS_MSG.date_end
	    	FROM `environment_health_safety_messages` EHS_MSG
	    	LEFT JOIN `message` MSG ON MSG.uid = EHS_MSG.message_fk
	    	LEFT JOIN `environment_health_safety` EHS ON EHS.uid = EHS_MSG.ehs_fk
	    	LEFT JOIN `account_basic_information` CREATED_BY ON CREATED_BY.auth_fk = MSG.created_by
	    	LEFT JOIN `message_recipients` RCPT ON RCPT.message_fk = MSG.uid
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
		public function __construct( Iconfig $CONFIG, EHSMessageSearchMeta $META )
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
			assert($META instanceof EHSMessageSearchMeta);

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