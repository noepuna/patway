<?php

/**
 * business logic for Support Ticket Search Meta
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

use App\IConfig;





Class SupportTicketSearchMeta extends \Core\API\PaginationMeta
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