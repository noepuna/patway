<?php

    /**
     * ...
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

    trait TPaginationFilterArithmeticOperators
    {
        /**
         * ...
         *
         * @var string
         */
        private string $_arithmetic_operator;





        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getArithmeticOperator() : string
        {
            return $this->_arithmetic_operator;
        }





        /**
         *  ...
         *
         *  @access private
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         */
        private function _setArithmeticOperator( string $ARITHMETIC_OPERATOR  ) :bool
        {
            if( in_array($ARITHMETIC_OPERATOR, self::ARITHMETIC_OPERATORS) )
            {
                $this->_arithmetic_operator = $ARITHMETIC_OPERATOR;

                return true;
            }

            return false;
        }
    }

?>