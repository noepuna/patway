<?php

	/**
	 * ...
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

	use App\IConfig;





	Class SendTypeMeta extends \App\AppMeta
	{
	    /**
	     * ...
	     *
	     * @var Array
	     * @access public
	     */
	    const ALL =
	    [
	    	'direct' => 1,
	    	'forward' => 2,
	    	'share' => 3
	    ];

	    /**
	     * ...
	     *
	     * @var string
	     * @access public
	     */
	    const DIRECT = self::ALL['direct'];
	    const FORWARD = self::ALL['forward'];
	    const SHARE = self::ALL['share'];





		public function __construct( \App\IConfig $CONFIG )
		{

		}
	}

?>