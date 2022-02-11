<?php

/**
 * Business logic for Observation Properties of Behavior Base Safety Search
 *
 * @category	App\Construction\BehaviorBaseSafety\Property\Search
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
namespace App\Construction\BehaviorBaseSafety\Property\Search;

use App\IConfig;
use App\Construction\BehaviorBaseSafety\Property\Search\PropertyUsageSearchMeta;





Class PropertyUsageSearch extends \Core\API\Pagination
{
    /**
     * ...
     *
     * @var string
     */
    protected string $_initial_sql =
    "
		SELECT PROP.category_fk AS `category_id`,
		DATA.property_fk AS `property_id`, PROP.name AS `property_name`,
		DATA.value AS `property_value`,
		DATA.deleted AS `property_deleted`,
		COUNT(DATA.value) AS `value_count`
    	FROM `bbs_observation_properties` DATA
    	INNER JOIN `bbs_observation_property` PROP ON PROP.uid = DATA.property_fk
    	INNER JOIN `bbs_observation` BBS_O ON BBS_O.uid = DATA.observation_fk
    	LEFT JOIN `office_staff` OFC_STAFF ON OFC_STAFF.auth_fk = BBS_O.created_by
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
	public function __construct( Iconfig $CONFIG, PropertyUsageSearchMeta $META )
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
		assert($META instanceof PropertyUsageSearchMeta);

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