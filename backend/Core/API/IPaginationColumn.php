<?php

    /**
     * Interface for API Pagination Column
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

    interface IPaginationColumn
    {
        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getName() : string;

        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getNameAlias() : string;
    }

?>