<?php

/**
 * business logic for Messaging Reply Search
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





Class SearchReplyMeta extends \Core\API\PaginationMeta
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
        'conversation' => "conversation"
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
        self::PROP['conversation']
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