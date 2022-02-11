<?php

    /**
     * Interface for a Member Account
     *
     * @category	App\Account\Member
     * @package    
     * @author     	Original Author <solanoreynan@gmail.com>
     * @copyright  
     * @license    
     * @version    	Beta 1.0.0
     * @link       
     * @see
     * @since      	Class available since Beta 1.0.0
     */

    namespace App\Account\Member;

    interface IMember extends \App\Account\IAccount
    {
        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getAuth() : \App\Auth\IAuth;

        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingFirstname() : string;

        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingLastname() : string;

        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingMiddlename() : ?string;

        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingEmail() : string;

        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingAddress() : string;

        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getBillingContactNumber() : string;
    }

?>