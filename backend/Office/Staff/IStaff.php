<?php

/**
 * Interface for an Office Staff
 *
 * @category    App\Office\Staff
 * @package    
 * @author      Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version     Beta 1.0.0
 * @link       
 * @see
 * @since       Class available since Beta 1.0.0
 */

namespace App\Office\Staff;

interface IStaff extends \App\Account\IAccount
{
    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getAdmin() : string;





    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getOffice() : string;





    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getDepartments() : Array;
}

?>