<?php

	/**
	 * business logic for Messaging Recipients Search
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
	use App\Messaging\Search\SearchReplyMeta;





	Class SearchReply extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	    	SELECT RE.uid AS `id`, RE.message, RE.date_created, RE.date_updated, RE.deleted,
	    	CONCAT(SENDER.firstname, ' ', SENDER.lastname) AS `sender_name`

	    	FROM `message` RE
	    	LEFT JOIN `message_recipients` RCPT ON RCPT.message_fk = RE.conversation
	    	LEFT JOIN `account_basic_information` SENDER ON SENDER.auth_fk = RE.created_by
	    	LEFT JOIN `message` MSG ON MSG.uid = RE.conversation
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
		public function __construct( Iconfig $CONFIG, SearchReplyMeta $META )
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
			assert($META instanceof SearchReplyMeta);

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