<?php

	/**
	 * ...
	 *
	 * @category	App\BehaviorBaseSafety\Messaging
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\BehaviorBaseSafety\Messaging;

	use App\IConfig;





	Class SentMessageMeta extends \App\Messaging\SentMessageMeta
	{
		use \App\Office\TOfficeOps,
			\App\BehaviorBaseSafety\TBBSObservationOps;

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
			'bbs_observation_message' =>
			[
				'observation_id' =>
				[
					'type' => "string",
					'null-allowed' => false
				]
			]
	    ];





		public function __construct( \App\IConfig $CONFIG )
		{
			Parent::__construct($CONFIG);

			$this->_config = $CONFIG;

			$this->_metadata += self::_metadata;
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
				case 'message':
					switch( $name )
					{
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

				case 'bbs_observation_message':
					switch( $name )
					{
						case 'observation_id':
							if( !$this->t_BBSObservationOps_exists($iConfig, $newValue) )
							{
								$this->setLastError("{$alias} not found");
							}

							$callback = function($settings, $crudMethod, $sender) use ($iConfig, $alias)
							{
								$observationId = $settings->getNewValue();

								if( !$this->t_BBSObservationOps_isAvailable($iConfig, $observationId, $sender->getId()) )
								{
									return "{$alias} not available";
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method", ["message", "sender"]);
						break;
					}
				break;
			}
		}
	}

?>