<?php

/**
 * business logic for Messaging Recipient Search
 *
 * @category    App\Messaging\Search
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\Messaging\Search;

use App\IConfig;





Class SearchRecipientMeta extends \Core\API\PaginationMeta
{
    /**
     * ...
     *
     * ...
     *
     * @var Resource\IConfig;
     * @access private
     */
    protected $_config;

    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access public
     */
    public const PROP =
    [
        'message_id' => "message_id",
        'sender_id' => "sender_id"
    ];

    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access public
     */
    public const FILTER =
    [
        self::PROP['message_id']
    ];

    /**
     * holds the table column names counterpart for the pagination filter names.
     * Note: for a filter to be included in the sql query, it must be present in this array.
     * Synopsis: array( 'pagination name' => "table column name" )
     *
     * @var array
     */
    /*protected $columnAlias =
    [
        'id' => "A.uid",
        'previlege' => "A_PR.previlege_fk"
    ];*/





    /**
     *
     */
    public function __construct( IConfig $CONFIG )
    {
        parent::__construct( $CONFIG );

        $this->_config = $CONFIG;
    }





    /**
     *  ...
     *
     *  @access public
     *  @param ...
     *  @return ...
     *  @since Method available since Beta 1.0.0
     *
     *  ...
     *  ...
     *  ...
     *  ...
     */
    protected function _setSpecialProperty( $SETTINGS )
    {
        parent::_setSpecialProperty($SETTINGS);

        if( $this->getLastError() )
        {
            return;
        }

        switch( $FIELD )
        {
            //
        }
    }
}