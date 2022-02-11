<?php

/**
 * Messaging Notification Search Columns
 *
 * @category    App\Messaging\Notification\Search
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





Class SearchColumn extends \Core\API\PaginationColumn
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
    	'id' => "NOTF.uid",
        'component' => "NOTF.app_component_fk",
        'actor' => "MSG.created_by",
        'date_created' => "MSG.date_created",
        'recipient' => "RCPT.recipient_fk"
    ];
}

?>