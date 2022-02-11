<?php

/**
 * ...
 *
 * @category	App\Auth
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
namespace App\Auth;

Class AuthPrevilegeMeta extends \App\AppMeta
{
    /**
     * ...
     *
     * @var string
     * @access public
     */
    const LEAD_BUYER    = 1;
    const MERCHANT      = 2;
    const SYSTEM        = 3;
    const OFFICE_OWNER  = 4;
    const ADMIN         = 5;
    const STAFF         = 6;

    /**
     * ...
     *
     * @var string
     * @access public
     */
    const ALL_PREVILEGES =
    [
        self::LEAD_BUYER,
        self::MERCHANT,
        self::SYSTEM,
        self::OFFICE_OWNER,
        self::ADMIN,
        self::STAFF
    ];

    /**
     * ...
     *
     * @var string
     * @access public
     */
    const ENABLED = 1;
    const DISABLED = 0;
}

?>