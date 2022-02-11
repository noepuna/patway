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

use Core\API\PaginationColumn;





Class PaginationFilter implements \Core\API\IPaginationFilter
{
    /**
     * ...
     *
     * @var Array
     */
    private $_logic_operators = [ PaginationFilter::_LOGIC_AND , PaginationFilter::_LOGIC_OR ];

    /**
     * ...
     *
     * @var string
     */
	public const _LOGIC_AND = 'AND';
	public const _LOGIC_OR = 'OR';

    /**
     * ...
     *
     * @var Array
     */
    private $_arithmetic_operators =
    [
    	PaginationFilter::_ARITHMETIC_EQUALS,
    	PaginationFilter::_ARITHMETIC_NOT_EQUALS,
    	PaginationFilter::_ARITHMETIC_LESSTHAN,
    	PaginationFilter::_ARITHMETIC_LESSTHAN_OR_EQUALS,
    	PaginationFilter::_ARITHMETIC_GREATERTHAN,
    	PaginationFilter::_ARITHMETIC_GREATERTHAN_OR_EQUALS,
    	PaginationFilter::_ARITHMETIC_CONTAINS,
    	PaginationFilter::_ARITHMETIC_STARTS_WITH
    ];

    /**
     * ...
     *
     * @var string
     */
   	public const _ARITHMETIC_EQUALS = '=';
   	public const _ARITHMETIC_LESSTHAN = '<';
   	public const _ARITHMETIC_LESSTHAN_OR_EQUALS = '<=';
   	public const _ARITHMETIC_GREATERTHAN = '>';
   	public const _ARITHMETIC_GREATERTHAN_OR_EQUALS = '>=';
	public const _ARITHMETIC_NOT_EQUALS = '!=';
	public const _ARITHMETIC_CONTAINS = "CONTAINS";
	public const _ARITHMETIC_STARTS_WITH = "STARTS WITH";

    /**
     * ...
     *
     * @var string
     */
	private PaginationColumn $_property;

    /**
     * ...
     *
     * @var string
     */
	private string $_logic_operator;

    /**
     * ...
     *
     * @var string
     */
	private string $_arithmetic_operator;

    /**
     * ...
     *
     * @var string | PaginationFilter
     */
	private $_value;





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 *
	 *	...
	 */
	public function __construct( PaginationColumn $PROPERTY, $VALUE, string $ARITHMETIC_OPERATOR, string $LOGIC_OPERATOR )
	{
		$this->_property = $PROPERTY;

		if( !$this->_setLogicOperator($LOGIC_OPERATOR) )
		{
			throw new \Exception("invalid value for operator", 1);
		}

		if( !$this->_setArithmeticOperator($ARITHMETIC_OPERATOR) )
		{
			throw new \Exception("invalid value for arithmetic", 1);
		}

		if( !$this->_setValue($VALUE) )
		{
			throw new \Exception("invalid value for arithmetic", 1);
		}
	}





	/**
	 *	...
	 *
	 * @access public
	 * @static
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 *
	 * ...
	 */
	public static function createInstance( $PROPERTY, $VALUE = NULL ) : ?self
	{
		$arithmeticOperator = func_get_arg(2);
		$logicOperator = func_get_arg(3);

		/*if( !is_string($NAME) || !is_string($arithmeticOperator) || !is_string($logicOperator) )
		{
			return NULL;
		}*/

		try
		{
			return new static($PROPERTY, $VALUE, $arithmeticOperator, $logicOperator);
		}
		catch (\Exception $e)
		{
			return NULL;
		}
	}





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 */
	public function getProperty() : PaginationColumn
	{
		return $this->_property;
	}





	/**
	 *	...
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
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 */
	private function _setLogicOperator( string $LOGIC_OPERATOR  ) :bool
	{
		if( in_array($LOGIC_OPERATOR, $this->_logic_operators) )
		{
			$this->_logic_operator = $LOGIC_OPERATOR;

			return true;
		}

		return false;
	}





	/**
	 *	...
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
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 */
	private function _setArithmeticOperator( string $ARITHMETIC_OPERATOR  ) :bool
	{
		if( in_array($ARITHMETIC_OPERATOR, $this->_arithmetic_operators) )
		{
			$this->_arithmetic_operator = $ARITHMETIC_OPERATOR;

			return true;
		}

		return false;
	}





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 */
	public function getValue()
	{
		return $this->_value;
	}





	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 */
	private function _setValue( $VALUE  )
	{
		if( is_scalar($VALUE) || is_array($VALUE) )
		{
			$this->_value = $VALUE;

			return true;
		}

		return false;
	}
}

?>