<?php

/**
 * Interface for Account
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

interface IAccount
{
    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getId() : ?string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getFirstName() : ?string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getLastName() : ?string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getMiddleName() : ?string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getEmail() : ?string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getLocationAddress() : ?string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getContactNumber() : ?string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getDateJoined() : int;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function taskAvailable( string... $ACTIONS ) : bool;
}

?>