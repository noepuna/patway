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

Class PaginationColumn implements \Core\API\IPaginationColumn
{
	use \Core\Util\TUtilOps;

    /**
     * holds the table column names counterpart for the pagination filter names.
     * Note: for a filter to be included in the sql query, it must be present in this array.
     * Synopsis: array( 'pagination name' => "table column name" )
     *
     * @var array
     */
    protected $_name_aliases = [];

    /**
     * ...
     *
     * @var string
     */
	private $_name;

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
	public function __construct( string $NAME )
	{
		if( !$this->_setName($NAME) )
		{
			throw new \Exception("invalid value for name", 1);
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
	public static function createInstance( $NAME ) : ?self
	{
		if( !is_string($NAME) )
		{
			return null;
		}

		try
		{
			return new static($NAME);
		}
		catch (\Exception $e)
		{
			return null;
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
	public function getName() : string
	{
		return $this->_name;
	}

	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 */
	private function _setName( string $NAME  ) : bool
	{
		if( array_key_exists($NAME, $this->_name_aliases) )
		{
			$this->_name = $NAME;

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
	public function getNameAlias() : string
	{
		return $this->_name_aliases[$this->_name];
	}
}

?>