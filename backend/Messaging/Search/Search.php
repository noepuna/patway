<?php

	/**
	 * business logic for Messaging Search
	 *
	 * @category	App\Messaging\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Messaging\Search;

	use App\IConfig;
	use App\Messaging\Search\SearchMeta;





	Class Search extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	    	SELECT MSG.uid AS `id`, MSG.message,
	    	MSG.recipient AS `recipient_id`, MSG.created_by AS `sender_id`, MSG.date_created, MSG.date_updated, MSG.deleted,
	    	CONCAT(SENDER.firstname, ' ', SENDER.lastname) AS `sender_name`

	    	FROM `message` MSG
	    	LEFT JOIN `account_basic_information` SENDER ON SENDER.auth_fk = MSG.created_by
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
		public function __construct( Iconfig $CONFIG, SearchMeta $META )
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
			assert($META instanceof SearchMeta);

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