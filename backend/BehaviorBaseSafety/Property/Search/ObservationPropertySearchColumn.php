<?php

/**
 * Behavior Base Safety Observation Property Search Columns
 *
 * @category    App\BehaviorBaseSafety\Property\Search
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





Class ObservationPropertySearchColumn extends \Core\API\PaginationColumn
{
	use \Core\Util\TUtilOps;

    /**
     * holds the table column names counterpart for the pagination filter names.
     * Note: for a filter to be included in the sql query, it must be present in this array.
     * Synopsis: array( 'pagination name' => "table column name" )
     *
     * @var array
     */
    protected $_name_aliases =
    [
        'id' => "BBS_P.uid",
        'name' => "BBS_P.name",
        'category' => "BBS_P.category_fk"
    ];
}

?>