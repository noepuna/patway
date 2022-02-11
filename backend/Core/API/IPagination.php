<?php

    /**
     * Interface for Pagination
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

    interface IPagination
    {
        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         */ 
        public function getLimit() : int;

        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         */ 
        public function getOffset() : int;

        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         */ 
        public function getIndexColumn() :? \Core\API\PaginationIndex;

        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         */ 
        public function getOrderBy() :? Array;

        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         */ 
        public function getGroupBy() :? Array;

        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         */ 
        public function getFilter() :? Array;

        /**
         *  ...
         *
         *  @access public
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         *
         *  ...
         */ 
        public function getResult() : Array;
    }

?>