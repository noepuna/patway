<?php

    /**
     * Interface for Pagination Filter Arithmetic Operators
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

    interface IPaginationFilterArithmeticOperators
    {
        /**
         * ...
         *
         * @var Array
         */
        const ARITHMETIC_OPERATORS =
        [
            "=",
            "<",
            ">",
            "!=",
            "CONTAINS",
            "STARTS WITH"
        ];

        /**
         * ...
         *
         * @var string
         */
        const ARITHMETIC_EQUALS = self::ARITHMETIC_OPERATORS[0];
        const ARITHMETIC_LESSTHAN = self::ARITHMETIC_OPERATORS[1];
        const ARITHMETIC_GREATERTHAN = self::ARITHMETIC_OPERATORS[2];
        const ARITHMETIC_NOT_EQUALS = self::ARITHMETIC_OPERATORS[3];
        const ARITHMETIC_CONTAINS = self::ARITHMETIC_OPERATORS[4];
        const ARITHMETIC_STARTS_WITH = self::ARITHMETIC_OPERATORS[5];
    }

?>