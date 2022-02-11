<?php

/**
 * Interface for Admin Account
 *
 * @category	App\Office\Admin
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\Office\Admin;

interface IAdmin extends \App\Account\IAccount
{
    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getOwner() : string;
	
    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function updateUserAccountStatus();
}

?>