<?php

	/**
	 * ...
	 *
	 * @category	App\SupportTicket
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\SupportTicket;

	use App\IConfig;
	use App\Account\Task\TaskMeta;





	Class TicketMeta extends \App\AppMeta
	{
		use \App\SupportTicket\TTicketOps;

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
				],
				'ticket' =>
				[
					'id' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'category' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'description' =>
					[
						'type' => "string",
						'length-max' => 225,
						'null-allowed' => false
					],
					'severity' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'status' =>
					[
						'type' => "string",
						'null-allowed' => false
					],
					'created_by' =>
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
				case 'ticket':
					switch( $name )
					{
						case "id":
							if( !$this->t_ticketOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$callback = function($settings, $crudMethod, $created_by) use ($iConfig, $alias)
							{
								$ticketId = $settings->getNewValue();

								switch( $crudMethod )
								{
									case TicketMeta::CRUD_METHOD_READ:
										if( !$this->t_ticketOps_isAvailable($iConfig, $ticketId, $created_by->getId()) )
										{
											return "{$alias} not available";
										}
									break;
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method", ["ticket", "created_by"]);
						break;

						case "category":
							if( !$this->t_ticketOps_categoryIsAvailable($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not available");
							}
						break;

						case "severity":
							if( !$this->t_ticketOps_severityIsAvailable($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not available");
							}
						break;

						case "status":
							if( !$this->t_ticketOps_statusIsAvailable($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not available");
							}
						break;

						case "created_by":
							//
							// basic validation
							//
							$isInvalidAccountCheck = function($iAccount, $alias)
							{
								//
								// account must already exist
								//
								if( !$iAccount->getId() )
								{
									return "{$alias} is invalid";
								}

								return false;
							};

							( $errorMsg = $isInvalidAccountCheck($newValue, $alias) ) && $this->setLastError($errorMsg);
						break;
					}
				break;
			}
		}
	}

?>