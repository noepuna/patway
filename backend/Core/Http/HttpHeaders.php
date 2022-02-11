<?php

	/**
	 * All Business Logic for HttpHeders.
	 *
	 * @category	Core\Http
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */

	namespace Core\Http;

	class HttpHeaders implements \Core\Http\IHttpHeaders
	{
		use \Core\Util\TUtilOps;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>;
	     * @access private
	     */
	    private $_prop =
	    [
	    	//
	    ];

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>;
	     * @access private
	     */
	    private $_headers =
	    [
	    	//
	    ];

		public function __construct()
		{
			$this->_headers = getallheaders();
		}

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getValueOf( string $HEADER ) : ?string
	    {
	    	return @$this->_headers[$HEADER];
	    }
	}

?>