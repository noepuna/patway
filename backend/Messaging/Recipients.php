<?php

/**
 *  ArrayIterator class for Messaging Recipients
 *
 * @category	App\Messaging
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\Messaging;





class Recipients implements \Countable, \IteratorAggregate, \ArrayAccess
{
    use \Core\Util\TUtilOps;

    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */
    private $_recipients = [];

    public function __construct(){}

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function append( \App\Messaging\IRecipient $RECIPIENT )
    {
        $this->_recipients[] = $RECIPIENT;
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
        return count($this->_recipients);
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
        foreach( $this->_recipients as $iRecipient)
        {
            yield $iRecipient;
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
        if (is_null($OFFSET))
        {
            $this->_recipients[] = $VALUE;
        }
        else
        {
            $this->_recipients[$OFFSET] = $VALUE;
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
        return isset( $this->_recipients[$OFFSET] );
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
        unset( $this->_recipients[$OFFSET] );
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
        return isset($this->_recipients[$OFFSET]) ? $this->_recipients[$OFFSET] : null;
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
        return $this->_recipients;
    }
}