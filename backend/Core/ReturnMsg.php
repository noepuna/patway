<?php

/**
 * ...
 *
 * @category	Resource
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace Resource;

class ReturnMsg
{
    /**
     * ...
     *
     * ...
     *
     * @var
     * @access public
     */
    const SUCCESS = 1;
    const FAILURE = 2;
    const INCOMPLETE = 3;
    const NO_CHANGES = 4;

    /**
     * ...
     *
     * default value for code
     *
     * @var
     * @access public
     */
    public $code = ReturnMsg::FAILURE;

    /**
     * ...
     *
     * default value for code
     *
     * @var
     * @access public
     */
    public $value;

    /**
     * ...
     *
     * default value for code
     *
     * @var
     * @access public
     */
    public $error;

	public function __construct()
	{
		//
	}

	/**
	 *	...
	 *
	 * @param ...
	 * @return ReturnMsg
	 *
	 * @access public
	 * @static
	 * @since Method available since Beta 1.0.0
     */
    public static function success( $DATA = null ) : self
    {
    	$returnMsg = new self;
    	$returnMsg->code = self::SUCCESS;
    	$returnMsg->value = $DATA;

    	return $returnMsg;
    }

	/**
	 *	...
	 *
	 * @param ...
	 * @return ReturnMsg
	 *
	 * @access public
	 * @static
	 * @since Method available since Beta 1.0.0
     */
    public static function failure( Array $ERRORS ) : self
    {
    	$returnMsg = new self;
    	$returnMsg->code = self::FAILURE;
    	$returnMsg->error = $ERRORS;

    	return $returnMsg;
    }

    /**
     *  ...
     *
     * @param ...
     * @return ReturnMsg
     *
     * @access public
     * @static
     * @since Method available since Beta 1.0.0
     */
    public static function noChanges( Array $ERRORS ) : self
    {
        $returnMsg = new self;
        $returnMsg->code = self::NO_CHANGES;
        $returnMsg->error = $ERRORS;

        return $returnMsg;
    }
}

?>