<?php

	/**
	 * ...
	 *
	 * @category	App\Messaging\Notification
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Messaging\Notification;

	use App\IConfig;





	Class NotificationMeta extends \App\AppMeta
	{
		use \App\Messaging\TMessagingOps;

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
			'notification' =>
			[
				'id' =>
				[
					'type' => "int",
					'null-allowed' => false
				],
				'message_id' =>
				[
					'type' => "int",
					'null-allowed' => false
				],
				'app_component' =>
				[
					'type' => "\App\Structure\Component",
					'null-allowed' => false,
				],
				'title' =>
				[
					'type' => "string",
					'length-max' => 64,
					'null-allowed' => false
				],
				'body' =>
				[
					'type' => "string",
					'length-max' => 64,
					'null-allowed' => true
				],
				'data' =>
				[

				]
			]
	    ];





		public function __construct( \App\IConfig $CONFIG )
		{
			$this->_config = $CONFIG;

			$this->_metadata =
			[
				'crud_method' =>
				[
					'type' => "enum",
					'collection' => [ self::CRUD_METHOD_CREATE, self::CRUD_METHOD_READ, self::CRUD_METHOD_UPDATE ],
					'null-allowed' => false
				]

			] + self::_metadata;
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
			$name = $SETTINGS->getName();
			$field = $SETTINGS->getField();
			$alias = $SETTINGS->getAlias() ?? $name;
			$newValue = $SETTINGS->getNewValue();

			switch( $field )
			{
				case 'notification':
					switch( $name )
					{
						case "id":
							//if( !$this->t_messagingOps_exists($iConfig, $newValue) )
							//{
								$this->setLastError("{$alias} not yet implemented");
							//}
						break;

						case "message_id":
							if( !$this->t_messagingOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}
						break;

						case "app_component":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} invalid");
							}
						break;
					}
				break;
			}
		}
	}

?>