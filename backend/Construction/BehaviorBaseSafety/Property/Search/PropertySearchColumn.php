<?php

/**
 * Observation Properties of Behavior Base Safety Search Columns
 *
 * @category    App\Construction\BehaviorBaseSafety\Property\Search
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





Class PropertyUsageSearchColumn extends \Core\API\PaginationColumn
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
        //
        // properties record details
        //
        'property_id' => "DATA.property_fk",
        'property_value' => "DATA.value",
        'property_deleted' => "DATA.deleted",
        //
        // behavior base safety details
        //
        'visibility' => "BBS_O.visibility_fk",
        'created_by' => "BBS_O.created_by",
        'supervisor' => "BBS_O.supervisor",
        'observer' => "BBS_O.observer",
        'date_created' => "BBS_O.date_created",
        //
        // property details
        //
        'category_id' => "PROP.category_fk",
        //
        // office
        //
        'office' => "OFC_STAFF.office_fk"
    ];
}

?>