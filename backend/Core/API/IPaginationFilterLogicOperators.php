<?php

    /**
     * Interface for Pagination Filter Logic Operators
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

    interface IPaginationFilterLogicOperators
    {
        /**
         * ...
         *
         * @var Array
         */
        const LOGIC_OPERATORS = [ "AND", "OR" ];

        /**
         * ...
         *
         * @var string
         */
        const LOGIC_AND = self::LOGIC_OPERATORS[0];
        const LOGIC_OR  = self::LOGIC_OPERATORS[1];
    }

?>