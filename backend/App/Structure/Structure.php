<?php

/**
 * ...
 *
 * @category	App\Structure
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\Structure;

	use App\IConfig;
	use App\Structure\StructureMeta;





	class Structure implements \App\Structure\IStructure
	{
		use \Core\Util\TUtilOps,
			\App\Event\TEventOps;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>
	     * @access private
	     */
	    private Array $_prop =
	    [
	    	'crud_method' => null,
	    	'app_structure' =>
	    	[
				'id' => null,
				'name' => null,
				'enabled' => true
	    	]
	    ];

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var App\IConfig;
	     * @access private
	     */
	    private const _CTOR_REQS =
	    [
	    	'create' => [ "crud_method", [ "app_structure", "id" ] ]
	    ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'structure' => false ];

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
		 * @access public
		 * @static
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function __construct( IConfig $CONFIG, StructureMeta $META )
		{
			$s = $META->app_structure;

			if( $META->require( ...self::_CTOR_REQS['create'] ) && StructureMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['app_structure'] =
				[
					'id' => $s['id']

				] + $this->_prop['app_structure'];
			}
			else
			{
				throw new \Exception("Invalid meta", 1);
			}

			$this->_prop =
			[
				'crud_method' => $META->crud_method

			] + $this->_prop;

			$this->_config = $CONFIG;
		}





		/**
		 *	...
		 *
		 * @access public
		 * @static
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public static function createInstance( IConfig $CONFIG, StructureMeta $META )
		{
			try
			{
				return new self($CONFIG, $META);
			}
			catch( \Exception $EXCEPTION )
			{
				return null;
			}
		}





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getId() : ?string
	    {
	    	return $this->_prop['structure']['id'] ?? null;
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getName() : string
        {
            $this->_requireStructureSegment();

            return $this->_prop['structure']['name'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function isEnabled() : bool
        {
            $this->_requireStructureSegment();

            return !!$this->_prop['structure']['enabled'];
        }





        /**
         *  ...
         *
         * @access private
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        private function _requireStructureSegment()
        {
            if( !$this->_propertySegment['structure'] && $this->getId() )
            {
                $e = self::t_eventOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['structure'] =
                [
					'id' => $e['id'],
					'title' => $e['title'],
					'description' => $e['description'],
					'location' => $e['location'],
					'closed' => $e['closed'],
					'created_by' => $e['created_by'],
					'date_created' => $e['date_created'],
					'deleted' => $e['deleted']
                ] + $this->_prop['event'];

                $this->_propertySegment['event'] = true;
            }
        }
	}

?>