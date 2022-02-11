<?php

	/**
	 * ...
	 *
	 * @category	App\File
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\File;

	use App\IConfig;
	use App\Account\Task\TaskMeta;





	Class FileVisibilityMeta extends \App\AppMeta
	{
	    /**
	     * ...
	     *
	     * @var string
	     * @access public
	     */
	    const TYPE_PUBLIC = 1;
	    const TYPE_PRIVATE   = 2;
	    const CUSTOM = 3;





		public function __construct( \App\IConfig $CONFIG )
		{

		}
	}

?>