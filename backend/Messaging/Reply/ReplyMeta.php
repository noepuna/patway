<?php

	/**
	 * ...
	 *
	 * @category	App\Messaging\Reply
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Messaging\Reply;

	use App\IConfig;





	Class ReplyMeta extends \App\Messaging\SentMessageMeta
	{
	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var App\IConfig;
	     * @access private
	     */
	    private IConfig $_config;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var App\IConfig;
	     * @access private
	     */
	    private const _metadata =
	    [
			'message' =>
			[
				'conversation' =>
				[
					'type' => "int",
					'null-allowed' => false
				]
			]
	    ];





		public function __construct( \App\IConfig $CONFIG )
		{
			Parent::__construct($CONFIG);

			$this->_config = $CONFIG;

			$this->_metadata['message'] = self::_metadata['message'] + $this->_metadata['message'];
		}
	}

?>