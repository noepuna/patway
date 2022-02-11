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

    trait TPaginationFilterLogicOperators
    {
        /**
         * ...
         *
         * @var string
         */
        private string $_logic_operator;





        /**
         *  ...
         *
         * @access public
         * @param ...
         * @return ...
         * @since Method available since Beta 1.0.0
         */
        public function getLogicOperator() : string
        {
            return $this->_logic_operator;
        }





        /**
         *  ...
         *
         *  @access private
         *  @param ...
         *  @return ...
         *  @since Method available since Beta 1.0.0
         */
        private function _setLogicOperator( string $LOGIC_OPERATOR  ) :bool
        {
            if( in_array($LOGIC_OPERATOR, self::LOGIC_OPERATORS) )
            {
                $this->_logic_operator = $LOGIC_OPERATOR;

                return true;
            }

            return false;
        }
    }

?>