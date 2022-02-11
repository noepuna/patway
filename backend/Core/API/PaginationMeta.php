<?php

	/**
	 * meta class for API pagination class 
	 *
	 * @category	Core\API
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace Core\API;

	use App\IConfig;





	Class PaginationMeta extends \App\AppMeta
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
		public function __construct( IConfig $CONFIG )
		{
			$this->_metadata =
			[
				'pagetoken',

				'limit' =>
				[
					'type' => "int",
					'unsigned' => false,
					'zero-allowed' => false,
					'decimal-allowed' => false
				],
				'offset' =>
				[
					'type' => "numeric"
				],
				'index_column' =>
				[
					'type' => "\Core\API\PaginationIndex",
					'null-allowed' => false
				],
				'filters' =>
				[
					'type' => "\Core\API\IPaginationFilter",
					'is-array' => true,
					'null-allowed' => true
				],
				'order_by' =>
				[
					'type' => "\Core\API\IPaginationOrderBy",
					'is-array' => true,
					'null-allowed' => true
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
			switch($FIELD)
			{
				default:
					# code...
					break;
			}
		}
	}

?>