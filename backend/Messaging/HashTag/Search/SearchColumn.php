<?php

/**
 * Messaging HashTag Search Columns
 *
 * @category    App\Messaging\HashTag\Search
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\Messaging\HashTag\Search;





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
        'id' => "MSG.uid",
        'recipient' => "RCPT.recipient_fk",
        'sender' => "MSG.created_by",       
        'hash_tag' => "HT.name",
        'deleted' => "MSG_HT.deleted"
    ];
}

?>