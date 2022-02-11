<?php

/**
 *  ArrayIterator class for Pagination Filter
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

use Core\API\IPaginationFilter;





class PaginationFilters implements \Countable, \IteratorAggregate, \ArrayAccess, \Core\API\IPaginationFilterLogicOperators
{
    use \Core\Util\TUtilOps, \Core\API\TPaginationFilterLogicOperators;

    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */
    private $_filters = [];

    public function __construct( string $LOGIC_OPERATOR = self::LOGIC_AND, Array $FILTERS = [] )
    {
        if( array_filter( $FILTERS, fn($filter) => !($filter instanceof IPaginationFilter) ) )
        {
            throw new \Exception("invalid filter found", 1);
        }
        else
        {
            $this->_filters = $FILTERS;
        }

        if( !$this->_setLogicOperator($LOGIC_OPERATOR) )
        {
            throw new \Exception("invalid value for operator", 1);
        }
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @static
     * @since Method available since Beta 1.0.0
     */
    public static function createInstance( string $LOGIC_OPERATOR = null, Array $FILTERS = [] )
    {
        try
        {
            return new PaginationFilters( $LOGIC_OPERATOR, $FILTERS );
        }
        catch( \Exception $e )
        {
            return false;
        }
        $this->_filters[] = $FILTERS;
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function append( IPaginationFilter $FILTER )
    {
        $this->_filters[] = $FILTER;
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function count() : int
    {
        return count($this->_filters);
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getIterator()
    {
        foreach( $this->_filters as $iFilter)
        {
            yield $iFilter;
        }
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function offsetSet( $OFFSET, $VALUE )
    {
        assert( $VALUE instanceof IPaginationFilter );

        if (is_null($OFFSET))
        {
            $this->_filters[] = $VALUE;
        }
        else
        {
            $this->_filters[$OFFSET] = $VALUE;
        }
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function offsetExists( $OFFSET )
    {
        return isset( $this->_filters[$OFFSET] );
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function offsetUnset( $OFFSET )
    {
        unset( $this->_filters[$OFFSET] );
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function offsetGet( $OFFSET )
    {
        return isset($this->_filters[$OFFSET]) ? $this->_filters[$OFFSET] : null;
    }

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function toArray() : Array
    {
        return $this->_filters;
    }
}