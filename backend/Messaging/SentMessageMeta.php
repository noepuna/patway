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





	Class SentMessageMeta extends \App\AppMeta
	{
		use \App\Account\TAccountOps,
			\App\Messaging\TMessagingOps;

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
			'message' =>
			[
				'id' =>
				[
					'type' => "int",
					'null-allowed' => false
				],
				'conversation' =>
				[
					'type' => "int",
					'null-allowed' => true
				],
				'type' =>
				[
					'type' => "string",
					'null-allowed' => false
				],
				'message' =>
				[
					'type' => "string",
					'length-max' => 128,
					'null-allowed' => true
				],
				'recipients' =>
				[
					'type' => "\App\Messaging\Recipient",
					'null-allowed' => true,
					'is-array' => true,
					//'count-min' => 1
				],
				'group' =>
				[
					'type' => "string",
					'null-allowed' => true
				],
				'sender' =>
				[
					'type' => "\App\Account\Account",
					'null-allowed' => false
				],
				'date_created' =>
				[
					'type' => "int",
					'zero-allowed' => false,
					'decimal-allowed' => false,
					'null-allowed' => false,
					'unsigned' => true
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
			$db = $iConfig->getDbAdapter();
			$name = $SETTINGS->getName();
			$field = $SETTINGS->getField();
			$alias = $SETTINGS->getAlias() ?? $name;
			$newValue = $SETTINGS->getNewValue();

			switch( $field )
			{
				case 'message':
					switch( $name )
					{
						case "id":
						case "conversation":
							if( !$this->t_messagingOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$sender = function( $settings, $crudMethod, $sender ) use ($iConfig, $alias)
							{
								$messageId = $settings->getNewValue();

								switch( $crudMethod )
								{
									case SentMessageMeta::CRUD_METHOD_CREATE:
									case SentMessageMeta::CRUD_METHOD_READ:
										if( !$this->t_messagingOps_isAvailable($iConfig, $messageId, $sender->getId()) )
										{
											return "{$alias} not available";
										}
									break;

									default:
										if( !$this->t_messagingOps_exists($iConfig, $messageId, $sender->getId()) )
										{
											return "{$alias} not found";
										}
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $sender, "crud_method", ["message", "sender"]);

							$accountForAccess = function($settings, $crudMethod, $account) use ($iConfig, $alias)
							{
								$messageId = $settings->getNewValue();

								switch( $crudMethod )
								{
									case SentMessageMeta::CRUD_METHOD_READ:
										if( !$this->t_messagingOps_isAvailable($iConfig, $messageId, $account->getId()) )
										{
											return "{$alias} not available";
										}
									break;
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $accountForAccess, "crud_method", ["message", "account_for_access"]);
						break;

						case "account_for_access":
							if( !$newValue->getId() )
							{
								$this->setLastError("{$alias} is invalid");
							}
						break;

						case "type":
							if( !$this->t_messagingOps_sendTypeExists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} is unsupported");
							}
						break;

						case "recipients":
							if( !($recipientId = $newValue->getId()) )
							{
								$this->setLastError("{$alias} is invalid");
							}
							else if( !$this->t_accountOps_exists($iConfig, $recipientId) )
							{
								$this->setLastError("{$alias} does not exist");
							}
						break;

						case "group":
							// validation here
						break;

						case "sender":
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