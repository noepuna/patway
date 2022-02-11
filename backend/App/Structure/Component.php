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
	use App\Structure\Structure as AppStructure;
	use App\Structure\StructureMeta as AppStructureMeta;
	use App\Structure\ComponentMeta;





	class Component implements \App\Structure\IComponent
	{
		use \Core\Util\TUtilOps,
			\App\Structure\TAppComponentOps;

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
	    	'app_component' =>
	    	[
				'id' => null,
				'name' => null,
				'structure' => null,
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
	    	'create' => [ "crud_method", [ "app_component", "id" ] ]
	    ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'component' => false, 'structure' => false ];

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
		public function __construct( IConfig $CONFIG, ComponentMeta $META )
		{
			$c = $META->app_component;

			if( $META->require( ...self::_CTOR_REQS['create'] ) && ComponentMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['app_component'] =
				[
					'id' => $c['id']

				] + $this->_prop['app_component'];
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
		public static function createInstance( IConfig $CONFIG, ComponentMeta $META )
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
	    	return $this->_prop['app_component']['id'] ?? null;
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
            $this->_requireComponentSegment();

            return $this->_prop['app_component']['name'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getStructure() : AppStructure
        {
            $this->_requireStructureSegment();

            return $this->_prop['app_component']['structure'];
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
            $this->_requireComponentSegment();

            return !!$this->_prop['app_component']['enabled'];
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
        private function _requireComponentSegment()
        {
            if( !$this->_propertySegment['component'] && $this->getId() )
            {
                $c = self::t_appComponentOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['app_component'] =
                [
					'id' => $c['id'],
					'name' => $c['name'],
					'structure_id' => $c['structure_id'],
					'enabled' => $c['enabled']

                ] + $this->_prop['app_component'];

                $this->_propertySegment['component'] = true;
            }
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
        	$this->_requireComponentSegment();

            if( !$this->_propertySegment['structure'] && $this->getId() )
            {
                $meta =
                [
                	'crud_method' => AppStructureMeta::CRUD_METHOD_READ,
                	'app_structure' =>
                	[
                		'id' => $this->_prop['app_component']['structure_id']
                	]
                ];

                $iMeta = AppStructureMeta::createInstance( $this->_config, $meta );

                $this->_prop['app_component']['structure'] = AppStructure::createInstance( $this->_config, $iMeta );

                $this->_propertySegment['structure'] = true;
            }
        }
	}

?>