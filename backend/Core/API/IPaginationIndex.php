<?php

    /**
     * Interface for API Pagination Order By
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

    interface IPaginationIndex
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
        public function getValue() :? string;

        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getOrder() : string;
    }

?>