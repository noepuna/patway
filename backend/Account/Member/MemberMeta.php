<?php

	/**
	 * ...
	 *
	 * @category	App\Account\Member
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App\Account\Member;

	use App\IConfig;
	use App\Auth\AuthPrevilegeMeta;

	Class MemberMeta extends \App\Account\AccountMeta
	{
	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Resource\IConfig;
	     * @access private
	     */
		private $_config;

		public function __construct( IConfig $CONFIG )
		{
			Parent::__construct($CONFIG);

			$this->_config = $CONFIG;

			$this->_metadata =
			[
				'member' =>
				[

				],

				'billing' =>
				[
					'firstname' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => true
					],
					'lastname' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => true
					],
					'middlename' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => true
					],
					'email' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => true
					],
					'address' =>
					[
						'type' => "string",
						'length-max' => 255,
						'null-allowed' => true
					],
					'tel_num' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 20,
						'null-allowed' => true
					],
					'mobile_num' =>
					[
						'type' => "string",
						'length-min' => 1,
						'length-max' => 64,
						'null-allowed' => true
					]
				]

			] + $this->_metadata;
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

			$iConfig = $this->_config;
			$db = $iConfig->getDbAdapter();
			$name = $SETTINGS->getName();
			$field = $SETTINGS->getField();
			$alias = $SETTINGS->getAlias() ?? $name;
			$newValue = $SETTINGS->getNewValue();

			switch( $field )
			{
				case 'account':
					switch( $name )
					{
						case 'auth':
							$callback = function( $settings, $crudMethod ) use ($iConfig, $alias)
							{
								$iAuth = $settings->getNewValue();

								switch( $crudMethod )
								{
									case MemberMeta::CRUD_METHOD_CREATE:
										if( $iAuth->getId() > 0 )
										{
											return "{$alias} is invalid";
										}
									break;

									case MemberMeta::CRUD_METHOD_READ:
									case MemberMeta::CRUD_METHOD_UPDATE:
										if( !$iAuth->getId() )
										{
											return "{$alias} is invalid";
										}
									break;
								}

								return false;
							};

							$this->_dependencyRegister($SETTINGS, $callback, "crud_method");
						break;
					}
				break;
			}
		}
	}

?>