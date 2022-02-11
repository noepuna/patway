<?php

	/**
	 * ...
	 *
	 * @category	App\Construction\EnvironmentHealthSafety\Messaging
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Construction\EnvironmentHealthSafety\Messaging;

	use App\IConfig;





	Class SentMessageMeta extends \App\Messaging\SentMessageMeta
	{
		use \App\Office\TOfficeOps,
			\App\Construction\EnvironmentHealthSafety\TEHSOps,
			\App\Construction\EnvironmentHealthSafety\Messaging\TEHSMessagingOps;

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
	     * @var string
	     * @access public
	     */
	    const STATUS_VOID = 1;
	    const STATUS_OPEN   = 2;
	    const STATUS_PENDING = 3;
	    const STATUS_CLOSED = 4;

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
				'recipients' =>
				[
					'type' => "\App\Messaging\Recipient",
					'null-allowed' => false,
					'is-array' => true,
					'count-min' => 1
				]
	    	],
			'ehs_message' =>
			[
				'ehs' =>
				[
					'type' => "string",
					'null-allowed' => false
				],
				'status' =>
				[
					'type' => "string",
					'null-allowed' => false
				],
				'title' =>
				[
					'type' => "string",
					'length-max' => 64,
					'null-allowed' => false
				],
				'location' =>
				[
					'type' => "string",
					'length-max' => 64,
					'null-allowed' => false
				],
				'description' =>
				[
					'type' => "string",
					'length-max' => 128,
					'null-allowed' => true
				],
				'risk_level' =>
				[
					'type' => "string",
					'null-allowed' => false
				],
				'date_start' =>
				[
					'type' => "int",
					'zero-allowed' => false,
					'decimal-allowed' => false,
					'null-allowed' => false,
					'unsigned' => true
				],
				'date_end' =>
				[
					'type' => "int",
					'zero-allowed' => false,
					'decimal-allowed' => false,
					'null-allowed' => true,
					'unsigned' => true
				]
			]
	    ];





		public function __construct( \App\IConfig $CONFIG )
		{
			Parent::__construct($CONFIG);

			$this->_config = $CONFIG;

			$this->_metadata['message'] = self::_metadata['message'] + $this->_metadata['message'];
			$this->_metadata += [ 'ehs_message' => self::_metadata['ehs_message'] ];
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
			Parent::_setSpecialProperty($SETTINGS);

			if( $this->getLastError() )
			{
				return;
			}

			$iConfig = $this->_config;
			$db = $iConfig->getDbAdapter();
			$name = $SETTINGS->getName();
			$field = $SETTINGS->getField();
			$alias = $SETTINGS->getAlias() ?? $name;
			$newValue = $SETTINGS->getNewValue();

			switch( $field )
			{
				case "ehs_message":
					switch( $name )
					{
						case "ehs":
							if( !$this->t_EHSOps_exists( $iConfig, $newValue ) )
							{
								$this->setLastError("{$alias} not found");
							}

							$info = $this->t_EHSOps_getSettingsById( $iConfig, $newValue );

							if( !$info || !$info['has_event'] )
							{
								$this->setLastError("{$alias} has no event access");
							}

							$coWorkerCallback = function( $settings, $coWorker ) use ($iConfig, $alias)
							{
								$ehsId = $settings->getNewValue();

								if( !$this->t_EHSOps_isAvailableByMember($iConfig, $ehsId, $coWorker->getId()) )
								{
									return "{$alias} not available";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $coWorkerCallback, [ "message", "sender" ]);
						break;

						case "status":
							if( !$this->t_EHSMessagingOps_statusExists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} is unsupported");
							}
						break;

						case "risk_level":
							if( !$this->t_EHSMessagingOps_riskLevelExists( $iConfig, $newValue ) )
							{
								$this->setLastError("{$alias} is unsupported");
							}
						break;
					}
				break;

				case 'message':
					switch( $name )
					{
						case "id":
							if( !$this->t_EHSMessagingOps_exists( $iConfig, $newValue ) )
							{
								$this->setLastError("{$alias} not found");
							}
						break;

						case "recipients":
							$callback = function( $settings, $creator ) use ( $iConfig, $alias )
							{
								if( !$this->t_officeOps_isCoworker( $iConfig, $settings->getNewValue()->getId(), $creator->getId() ) )
								{
									return "{$alias} not found";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, ['message', 'sender']);
						break;

						case "group":
							// validation here
						break;
					}
				break;

				case 'notification':
					switch( $name )
					{
						case "app_component":
							//
							// require the right component here
							//
						break;

						case "payload":
							/*-------title-----------
							-------body------------
							-------data------------*/
						break;
					}
				break;
			}
		}
	}

?>