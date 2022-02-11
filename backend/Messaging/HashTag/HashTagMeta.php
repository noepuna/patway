<?php

	/**
	 * ...
	 *
	 * @category	App\Messaging\HashTag
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Messaging\HashTag;

	use App\IConfig;





	Class HashTagMeta extends \App\HashTag\HashTagMeta
	{
	    /**
	     * ...
	     *
	     * @var string
	     * @access public
	     */
	    const CRUD_METHOD_CREATE = "create";
	    const CRUD_METHOD_READ   = "read";
	    const CRUD_METHOD_UPDATE = "update";





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
			'message_hashtag' =>
			[
				'sent_message' =>
				[
					'type' => "\App\Messaging\SentMessage",
					'null-allowed' => false
				],
				'deleted' =>
				[
					'type' => "boolean",
					'null-allowed' => false
				]
			]
	    ];





		public function __construct( \App\IConfig $CONFIG )
		{
			Parent::__construct($CONFIG);

			$this->_config = $CONFIG;

			$this->_metadata['message_hashtag'] = self::_metadata['message_hashtag'];
		}





		/**
		 *	...
		 *
		 *	@access public
		 *	@param ...
		 *	@return ...
		 *	@since Method available since Beta 1.0.0
		 *
		 *	...
		 *	...
		 *	...
		 *	...
		 */
		protected function _setSpecialProperty( $SETTINGS )
		{


			$iConfig = $this->_config;
			$db = $iConfig->getDbAdapter();
			$name = $SETTINGS->getName();
			$field = $SETTINGS->getField();
			$alias = $SETTINGS->getAlias() ?? $name;
			$newValue = $SETTINGS->getNewValue();

			switch( $field )
			{
				case 'message_hashtag':
					switch( $name )
					{
						case "sent_message":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} is invalid");
							}
						break;
					}
				break;
			}
		}
	}

?>