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





Class PaginationOrderBy implements \Core\API\IPaginationOrderBy
{
    /**
     * ...
     *
     * @var string
     */
	public const _DESC = "DESC";
	public const _ASC = "ASC";


    /**
     * ...
     *
     * @var string
     */
    public const _VALUES =
    [
    	self::_DESC,
    	self::_ASC
    ];

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
	private string $_value;





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
	public function __construct( PaginationColumn $PROPERTY, string $VALUE )
	{
		$this->_property = $PROPERTY;

		if( !$this->_setValue($VALUE) )
		{
			throw new \Exception("invalid value for value", 1);
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
	public static function createInstance( PaginationColumn $PROPERTY, string $VALUE ) :? self
	{
		try
		{
			return new static($PROPERTY, $VALUE);
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
	public function getValue() : string
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
		if( in_array($VALUE, $this::_VALUES) )
		{
			$this->_value = $VALUE;

			return true;
		}

		return false;
	}
}

?>