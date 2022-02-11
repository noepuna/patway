<?php

	/**
	 * business logic for Messaging Notification Search
	 *
	 * @category	App\Messaging\Notification\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Messaging\Notification\Search;

	use App\IConfig;
	use App\Messaging\Notification\Search\SearchMeta;





	Class Search extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	    	SELECT NOTF.uid AS `id`, NOTF.message_fk AS `messge_id`,
	    	MSG.conversation, MSG.created_by AS `sender_id`, MSG.date_created, RCPT.recipient_fk AS `recipient`,
	    	CONCAT(SENDER.firstname, ' ', SENDER.lastname) AS `sender_name`,
	    	NOTF.app_component_fk AS `app_component`, NOTF.payload

	    	FROM `notification` NOTF
	    	INNER JOIN `message` MSG ON MSG.uid = NOTF.message_fk
	    	INNER JOIN `account_basic_information` SENDER ON SENDER.auth_fk = MSG.created_by
	    	INNER JOIN `message_recipients` RCPT ON MSG.uid = RCPT.message_fk
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