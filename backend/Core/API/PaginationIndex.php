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





Class PaginationIndex implements \Core\API\IPaginationIndex
{
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
	public const _DESC = self::_ORDER[0];
	public const _ASC = self::_ORDER[1];

    /**
     * ...
     *
     * @var string
     */
    public const _ORDER = [ "DESC", "ASC" ];

    /**
     * ...
     *
     * @var string
     */
	private string $_order = self::_DESC;

    /**
     * ...
     *
     * @var string
     */
	private ?string $_value;





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
	public function __construct( PaginationColumn $PROPERTY, string $VALUE = NULL, string $ORDER = null )
	{
		$this->_property = $PROPERTY;

		if( $ORDER && !$this->_setOrder($ORDER) )
		{
			throw new \Exception("invalid value for order", 1);
		}

		$this->_value = $VALUE;
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
	public static function createInstance( PaginationColumn $PROPERTY, string $VALUE = NULL, string $ORDER = null ) :? self
	{
		try
		{
			return new static($PROPERTY, $VALUE, $ORDER);
		}
		catch( \Exception $e )
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
	public function getValue() :? string
	{
		return $this->_value;
	}





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 */
	public function getOrder() : string
	{
		return $this->_order;
	}





	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 */
	private function _setOrder( $ORDER  )
	{
		if( in_array($ORDER, $this::_ORDER) )
		{
			$this->_order = $ORDER;

			return true;
		}

		return false;
	}
}

?>