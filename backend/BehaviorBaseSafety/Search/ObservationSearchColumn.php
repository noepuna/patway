<?php

/**
 * Behavior Base Safety Observation Search Columns
 *
 * @category    App\BehaviorBaseSafety\Search
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





Class ObservationSearchColumn extends \Core\API\PaginationColumn
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
    	'id' => "BBS_O.uid",
        'visibility' => "BBS_O.visibility_fk",
        'created_by' => "BBS_O.created_by",
        'date_created' => "BBS_O.date_created",
        'supervisor' => "BBS_O.supervisor",
        'observer' => "BBS_O.observer",
        'deleted' => "BBS_O.deleted",
        'co_worker' => "OFC_STAFF.auth_fk"
    ];
}

?>