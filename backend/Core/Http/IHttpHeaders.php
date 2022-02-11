<?php

    /**
     * Interface for HttpHeaders
     *
     * @category	Core\Http
     * @package    
     * @author     	Original Author <solanoreynan@gmail.com>
     * @copyright  
     * @license    
     * @version    	Beta 1.0.0
     * @link       
     * @see
     * @since      	Class available since Beta 1.0.0
     */

    namespace Core\Http;

    interface IHttpHeaders
    {
         /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getValueOf( string $HEADER ) : ?string;
    }

?>