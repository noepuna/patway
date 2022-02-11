<?php

/**
 * Support Ticket Search Columns
 *
 * @category    App\SupportTicket\Search
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\SupportTicket\Search;





Class SupportTicketSearchColumn extends \Core\API\PaginationColumn
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
    	'id' => "S_TKT.uid",
        'created_by' => "S_TKT.created_by",
        'date_created' => "S_TKT.date_created"
    ];
}

?>