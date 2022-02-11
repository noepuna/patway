<?php

/**
 * ...
 *
 * @category	App\Account
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
namespace App\Account;

use App\IConfig;

Class AccountAddressMeta extends \App\AppMeta
{
    /**
     * ...
     *
     * @var string
     * @access public
     */
    const TYPE_LOCATION = 1;
    const TYPE_BILLING  = 2;
    const TYPE_SHIPPING = 3;
}

?>