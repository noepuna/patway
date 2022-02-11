<?php

/**
 * Environment Health & safety Search Columns
 *
 * @category    App\EnvironmentHealthSafety\Search
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\EnvironmentHealthSafety\Search;





Class EHSSearchColumn extends \Core\API\PaginationColumn
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
    	'id' => "EHS.uid",
        'date_created' => "EHS.date_created",
        'created_by' => "EHS.created_by",
        'co_worker' => "OFC.auth_fk"
    ];
}

?>