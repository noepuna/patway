<?php

/**
 * ...
 *
 * @category	App\HashTag
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\HashTag;

	use App\IConfig,
		App\HashTag\HashTagMeta;





	class HashTag implements \App\HashTag\IHashTag
	{
		use \Core\Util\TUtilOps,
			\App\HashTag\THashTagOps;

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
	    	'hashtag' =>
	    	[
				'id' => null,
				'name' => null,
				'date_created' => null,
				'disabled' => false
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
        private $_propertySegment = [ 'hashtag' => false ];

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
        			"hashtag",
        			"name",
        			"created_by"
        		]
        	],
        	'read' =>
        	[
        		"crud_method",
        		[
                    "hashtag",
                    "name",
                    "created_by"
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
		public function __construct( IConfig $CONFIG, HashTagMeta $META )
		{
			$h = $META->hashtag;

			if( $META->require(...self::_META_REQS['create']) && HashTagMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['hashtag'] = 
				[
					'name' => $h['name'],
					'date_created' => $CONFIG->getCurrentTime(),
                    'disabled' => false

				] + $this->_prop['hashtag'];
			}
			else if( $META->require(...self::_META_REQS['read']) && HashTagMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['hashtag'] =
				[
					'name' => $h['name']

				] + $this->_prop['hashtag'];
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
		public static function createInstance( IConfig $CONFIG, HashTagMeta $META )
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
			$this->_requireHashTagSegment();

	    	return $this->_prop['hashtag']['id'] ?? null;
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
	    	return $this->_prop['hashtag']['name'];
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDateCreated() : int
        {
            $this->_requireHashTagSegment();

            return $this->_prop['hashtag']['date_created'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function isDisabled() : bool
        {
            $this->_requireHashTagSegment();

            return $this->_prop['hashtag']['disabled'];
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
        private function _requireHashTagSegment()
        {
        	# name is given
        	# if name exists - np
        	# in name is new
            if( !$this->_propertySegment['hashtag'] && $this->getName() )
            {
                if( $h = self::t_HashTagOps_getInfo($this->_config, $this->getName()) )
                {
	                $this->_prop['hashtag'] =
	                [
						'id' => $h['id'],
						'name' => $h['name'],
						'date_created' => $h['date_created'],
						'disabled' => $h['disabled']

	                ] + $this->_prop['hashtag'];
                }

                $this->_propertySegment['hashtag'] = true;
            }
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
			if( $this->getId() )
			{
				return $this->getId();
			}

			$errors = [];
			$iConfig = $this->_config;

			$param['hashtag'] =
			[
				'name' => $this->getName(),
				'date_created' => $this->getDateCreated(),
				'disabled' => $this->isDisabled()
			];

			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			# save the details

			$iDb->query
			("
				INSERT INTO `hash_tag`(`name`,`date_created`, `disabled`)
				VALUES (:name, :date_created, :disabled)",

				$param['hashtag']
			);

			$hashtagId = $iDb->lastInsertId();

			$this->_prop['hashtag']['id'] = $hashtagId;

			$dbTransaction && $this->getId() && $iDb->commit();

			return $this->getId();
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
		public function update( HashTagMeta $META ) : ?bool
		{
	    	return true;
		}
	}

?>