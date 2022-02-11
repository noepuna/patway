<?php

	/**
	 * ...
	 *
	 * @category	App\Auth
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Auth;

	Class AuthStatusMeta extends \App\AppMeta
	{
	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var int;
	     * @access public
	     */
		const ACTIVE = 1;
		const SUSPENDED = 2;
		const UNVERIFIED = "3";

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var App\IConfig;
	     * @access private
	     */
		private $_config;

		/**
		 *	...
		 *
		 * @access public
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function __construct( \App\IConfig $CONFIG )
		{
			$this->_config = $CONFIG;

			$this->_metadata = [];
		}
	}

?>