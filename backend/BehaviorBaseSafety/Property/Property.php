<?php

/**
 * ...
 *
 * @category	App\BehaviorBaseSafety\Property
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\BehaviorBaseSafety\Property;

	use App\IConfig;
	use App\BehaviorBaseSafety\Property\PropertyMeta;





	class Property implements \App\BehaviorBaseSafety\Property\IProperty
	{
		use \Core\Util\TUtilOps,
			\App\BehaviorBaseSafety\Property\TBBSObservationPropertyOps;

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
	    	'bbs_observation_property' =>
	    	[
				'id' => null,
				'value' => null,
				'count' => null,
				'deleted' => false
	    	]
	    ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'bbs_observation_property' => false ];

        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private const _META_REQS =
        [
        	'create' =>
        	[
        		"crud_method",
        		[
        			"bbs_observation_property",
        			'observation',
        			"id",
        			"value"
        		]
        	],
        	'read' =>
        	[
        		"crud_method",
        		[
        			"bbs_observation_property",
        			"observation",
        			"id",
        			"value",
        		]
        	],
        	'update' =>
        	[
        		"crud_method",
        		[
        			"bbs_observation_property",
        			"observation",
        			"id",
        			"value"
        		]
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
		public function __construct( IConfig $CONFIG, PropertyMeta $META )
		{
			$p = $META->bbs_observation_property;

			if( $META->require(...self::_META_REQS['create']) && PropertyMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['bbs_observation_property'] = 
				[
					'observation' => $p['observation'],
					'id' => $p['id'],
					'value' => $p['value'],
					'count' => $p['count'] ?? $this->getCount()

				] + $this->_prop['bbs_observation_property'];
			}
			else if( $META->require(...self::_META_REQS['read']) && PropertyMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['bbs_observation_property'] =
				[
					'observation' => $p['observation'],
					'id' => $p['id'],
					'value' => $p['value'],
					'count' => $p['count'] ?? $this->getCount()

				] + $this->_prop['bbs_observation_property'];
			}
			else if( $META->require(...self::_META_REQS['update']) && PropertyMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop['bbs_observation_property'] =
				[
					'observation' => $p['observation'],
					'id' => $p['id'],
					'value' => $p['value'],
					'count' => $p['count'] ?? $this->getCount()

				] + $this->_prop['bbs_observation_property'];
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
		public static function createInstance( IConfig $CONFIG, PropertyMeta $META )
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
	    public function getId() : string
	    {
	    	return $this->_prop['bbs_observation_property']['id'];
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getObservation() : \App\BehaviorBaseSafety\Observation
        {
            return $this->_prop['bbs_observation_property']['observation'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getValue() :? string
        {
            $this->_requirePropertySegment();

            return $this->_prop['bbs_observation_property']['value'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getCount() :? int
        {
            $this->_requirePropertySegment();

            return $this->_prop['bbs_observation_property']['count'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function isDeleted() : bool
        {
            $this->_requirePropertySegment();

            return $this->_prop['bbs_observation_property']['deleted'];
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
        private function _requirePropertySegment()
        {
            /*if( !$this->_propertySegment['bbs_observation_property'] && $this->getId() )
            {
                $o = self::t_BBSObservationOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['bbs_observation'] =
                [
					'id' => $o['id'],
					'types' => $o['types'],
					'observer' => $o['observer'],
					'supervisor' => $o['supervisor'],
					'notes' => $o['notes'],
					'recommendation' => $o['recommendation'],
					'action_taken' => $o['action_taken'],
					'feedback_to_coworkers' => $o['feedback_to_coworkers'],
					'created_by' => $o['created_by'],
					'date_created' => $o['date_created'],
					'deleted' => $o['deleted']

                ] + $this->_prop['bbs_observation'];

                $this->_propertySegment['bbs_observation'] = true;
            }*/
        }





		/**
		 *	...
		 *
		 * @access public
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		public function create() : ?string
		{
			$errors = [];
			$iConfig = $this->_config;

			/*
			 * save the data
			 */
			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			$param['bbs_observation_property'] =
			[
				'observation' => $this->getObservation()->getId(),
				'property_id' => $this->getId(),
				'value' => $this->getValue(),
				'count' => $this->getCount(),
				'deleted' => $this->isDeleted()
			];

			$iDb->query
			("
				INSERT INTO `bbs_observation_properties`
				( `observation_fk`, `property_fk`, `value`, `count`, `deleted` )
				VALUES
				( :observation, :property_id, :value, :count, :deleted )",

				$param['bbs_observation_property']
			);

			return $dbTransaction && $iDb->commit();
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
		public function update( PropertyMeta $META ) : ?bool
		{
			//
			// requiring crud_method, id and created_by is essential in validating other properties
			//
			$updateRequirements =
			[
				"crud_method",
				[
					"bbs_observation_property", "id", "observation"
				]
			];

			//
			// crud_method must be update
			// provide same id's in changeMeta and in the class constructor
			//
			if( $META->require(...$updateRequirements) && $META->crud_method === PropertyMeta::CRUD_METHOD_UPDATE )
			{
				$prop = $META->bbs_observation_property;
				$propId = $prop['id'] ?? null;

				if( $this->getId() !== $propId )
				{
					return false;
				}
			}
			else
			{
				return false;
			};

			//
			// certain properties can only be made changable
			//
			// synopsis: [ database table column name => property name ]
			//
	    	$allowedProperties['bbs_observation_property'] =
	    	[
	    		[ "count", "count" ],
	    		[ "value", "value" ],
	    		[ "deleted", "deleted" ]
	    	];

	    	$insertFieldQry['bbs_observation_property'] = [ "property_fk", "observation_fk" ];
	    	$insertValueQry['bbs_observation_property'] = [ ":property_fk", ":observation_fk" ];

	    	$param = $fieldSetQry = [];

	    	foreach($allowedProperties as $segment => $props)
	    	{
	    		if( !($segmentProp = $META->$segment) )
	    		{
	    			continue;
	    		}

	    		foreach( $props as [$column, $name] )
	    		{
	    			if( array_key_exists($name, $segmentProp) )
	    			{
	    				$param[$segment][$name] = $segmentProp[$name];

	    				$insertFieldQry[$segment][] = "`{$column}`";
	    				$insertValueQry[$segment][] = ":{$name}";
	    				$onDuplicateQry[$segment][] = "`{$column}` = VALUES(`{$column}`)";
	    			}
	    		}
	    	}

	    	//
	    	// empty properties
	    	//
	    	if( !$param )
	    	{
	    		return null;
	    	}

	    	//
	    	// save the changes
	    	//
	    	$iDb = $this->_config->getDbAdapter();
	    	$dbTransaction = $iDb->beginTransaction();

	    	if( $param['bbs_observation_property'] ?? null )
	    	{
	    		$param['bbs_observation_property']['property_fk'] = $this->getId();
	    		$param['bbs_observation_property']['observation_fk'] = $this->getObservation()->getId();

	    		$insertFieldQry = implode(", ", $insertFieldQry['bbs_observation_property']);
	    		$insertValueQry = implode(", ", $insertValueQry['bbs_observation_property']);
	    		$onDuplicateQry = implode(", ", $onDuplicateQry['bbs_observation_property']);

				$iDb->query
				("
					INSERT INTO `bbs_observation_properties`({$insertFieldQry})
					VALUES({$insertValueQry})
					ON DUPLICATE KEY UPDATE {$onDuplicateQry}",

					$param["bbs_observation_property"]
				);

				$this->_propertySegment['bbs_observation_property'] = false;
	    	}

	    	$dbTransaction && $iDb->commit();

	    	return true;
		}
	}

?>