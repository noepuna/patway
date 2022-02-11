<?php

	/**
	 * business logic for Behavior Base Safety Observation Property Search
	 *
	 * @category	App\BehaviorBaseSafety\Property\Search
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\BehaviorBaseSafety\Property\Search;

	use App\IConfig;
	use App\BehaviorBaseSafety\Property\Search\ObservationPropertySearchMeta;





	Class ObservationPropertySearch extends \Core\API\Pagination
	{
	    /**
	     * ...
	     *
	     * @var string
	     */
	    protected string $_initial_sql =
	    "
	    	SELECT BBS_P.uid AS `id`, BBS_P.name, category_fk AS `category`
	    	FROM bbs_observation_property BBS_P
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
		public function __construct( Iconfig $CONFIG, ObservationPropertySearchMeta $META )
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
			assert($META instanceof ObservationPropertySearchMeta);

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