<?php

    /**
     * Interface for Lead API Pagination Filter
     *
     * @category	Core\API
     * @package    
     * @author     	Original Author <solanoreynan@gmail.com>
     * @copyright  
     * @license    
     * @version    	Beta 1.0.0
     * @link       
     * @see
     * @since      	Class available since Beta 1.0.0
     */

    namespace Core\API;

    interface IPaginationFilter
    {
        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getProperty() : \Core\API\PaginationColumn;

        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getLogicOperator() : string;

        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getArithmeticOperator() : string;

        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getValue();
    }

?>