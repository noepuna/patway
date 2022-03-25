<?php

/**
 * ...
 *
 * @category	App\BehaviorBaseSafety
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\BehaviorBaseSafety;

	use App\IConfig,
		App\File\File as AppFile,
		App\File\FileMeta as AppFileMeta,
		App\BehaviorBaseSafety\ObservationMeta,
		App\BehaviorBaseSafety\Property\DumbProperty;





	class Observation implements \App\BehaviorBaseSafety\IObservation
	{
		use \Core\Util\TUtilOps,
			\App\BehaviorBaseSafety\TBBSObservationOps,
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
	    	'bbs_observation' =>
	    	[
				'id' => null,
				'visibility' => null,
				'types' => [],
				'observer' => null,
				'supervisor' => null,
				'notes' => null,
				'recommendation' => null,
<<<<<<< HEAD
				'action_required' => null,
=======
>>>>>>> 50cddb0018e73587d801050aa03ad33cec65b210
				'action_taken' => null,
				'feedback_to_coworkers' => null,
				'properties' => [],
				'attachment_file' => null,
				'attachment_file_id' => null,
				'created_by' => null,
				'date_created' => null,
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
        private $_propertySegment = [ 'bbs_observation' => false, 'properties' => false, 'attachment_file' => false ];

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
        			"bbs_observation",
        			"visibility",
        			"observer",
        			"recommendation",
        			"notes",
        			"action_taken",
        			"feedback_to_coworkers",
        			"supervisor",
        			"attachment_file",
        			"created_by"
        		]
        	],
        	'read' =>
        	[
        		"crud_method",
        		[
        			"bbs_observation",
        			"id",
        			"created_by"
        		]
        	],
        	'read_by_coworker' =>
        	[
        		"crud_method",
        		[
        			"bbs_observation",
        			"id",
        			"co_worker"
        		]
        	],
        	'update' =>
        	[
        		"crud_method",
        		[
        			"bbs_observation",
        			"id",
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
		public function __construct( IConfig $CONFIG, ObservationMeta $META )
		{
			$o = $META->bbs_observation;

			if( $META->require(...self::_META_REQS['create']) && ObservationMeta::CRUD_METHOD_CREATE === $META->crud_method )
			{
	    		$this->_prop['bbs_observation'] = 
				[
					'visibility' => $o['visibility'],
					'observer' => $o['observer'],
					'supervisor' => $o['supervisor'],
					'notes' => $o['notes'] ?? $this->getNotes(),
					'recommendation' => $o['recommendation'] ?? $this->getRecommendation(),
					'action_taken' => $o['action_taken'] ?? $this->getActionTaken(),
					'feedback_to_coworkers' => $o['feedback_to_coworkers'] ?? $this->getFeedbackToCoworkers(),
					'attachment_file' => $o['attachment_file'],
					'created_by' => $o['created_by']->getId(),
					'date_created' => $CONFIG->getCurrentTime()

				] + $this->_prop['bbs_observation'];

				//$this->_prop['bbs_observation']['types'] += $o['types'];
			}
			else if( $META->require(...self::_META_REQS['read']) && ObservationMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['bbs_observation'] =
				[
					'id' => $o['id']

				] + $this->_prop['bbs_observation'];
			}
			else if( $META->require(...self::_META_REQS['read_by_coworker']) && ObservationMeta::CRUD_METHOD_READ === $META->crud_method )
			{
				$this->_prop['bbs_observation'] =
				[
					'id' => $o['id']

				] + $this->_prop['bbs_observation'];
			}
			else if( $META->require(...self::_META_REQS['update']) && ObservationMeta::CRUD_METHOD_UPDATE === $META->crud_method )
			{
				$this->_prop['bbs_observation'] =
				[
					'id' => $o['id']

				] + $this->_prop['bbs_observation'];
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
		public static function createInstance( IConfig $CONFIG, ObservationMeta $META )
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
	    	return $this->_prop['bbs_observation']['id'] ?? null;
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getVisibility() : string
	    {
            $this->_requireBBSObservationSegment();

	    	return $this->_prop['bbs_observation']['visibility'];
	    }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getTypes() : Array
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['types'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getObserver() : string
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['observer'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getSupervisor() : string
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['supervisor'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getNotes() : string
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['notes'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getRecommendation() : string
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['recommendation'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getActionTaken() : string
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['action_taken'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getFeedbackToCoworkers() : string
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['feedback_to_coworkers'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getProperties() : Array
        {
            $this->_requirePropertySegment();

            return $this->_prop['bbs_observation']['properties'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getAttachmentFile() : ?AppFile
        {
            $this->_requireAttachmentFileSegment();

            return $this->_prop['bbs_observation']['attachment_file'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getCreatedBy() : string
        {
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['created_by'];
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
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['date_created'];
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
            $this->_requireBBSObservationSegment();

            return $this->_prop['bbs_observation']['deleted'];
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
        private function _requireBBSObservationSegment()
        {
            if( !$this->_propertySegment['bbs_observation'] && $this->getId() )
            {
                $o = self::t_BBSObservationOps_getInfoById( $this->_config, $this->getId() );

                $this->_prop['bbs_observation'] =
                [
					'id' => $o['id'],
					'visibility' => $o['visibility'],
					'types' => $o['types'],
					'observer' => $o['observer'],
					'supervisor' => $o['supervisor'],
					'notes' => $o['notes'],
					'recommendation' => $o['recommendation'],
					'action_taken' => $o['action_taken'],
					'feedback_to_coworkers' => $o['feedback_to_coworkers'],
					'attachment_file_id' => $o['attachment_file'],
					'created_by' => $o['created_by'],
					'date_created' => $o['date_created'],
					'deleted' => $o['deleted']

                ] + $this->_prop['bbs_observation'];

                $this->_propertySegment['bbs_observation'] = true;
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
        private function _requirePropertySegment()
        {
        	$this->_requireBBSObservationSegment();

        	if( !$this->_propertySegment['properties'] && $this->getId() )
        	{
        		$iProperties = [];

        		if( $properties = $this->t_BBSObservationPropertyOps_getByObserverId( $this->_config, $this->getId() ) )
        		{
        			foreach( $properties as $property )
        			{
        				$iProperty = $iProperties[] = new DumbProperty( $this->_config );

        				$iProperty->observer = $this;
        				$iProperty->id = $property['id'];
        				$iProperty->value = $property['value'];
        			}
        		}

        		$this->_prop['bbs_observation']['properties'] = $iProperties;

        		$this->_propertySegment['properties'] = true;
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
        private function _requireAttachmentFileSegment()
        {
        	$this->_requireBBSObservationSegment();

            $attachment_file_id = $this->_prop['bbs_observation']['attachment_file_id'];

            if( !$this->_propertySegment['attachment_file'] && $this->getId() && $attachment_file_id )
            {
            	$meta =
            	[
            		'crud_method' => AppFileMeta::CRUD_METHOD_READ,
            		'app_file' =>
            		[
            			'id' => $attachment_file_id
            		]
            	];

            	$iConfig = $this->_config;
            	$iMeta = AppFileMeta::createInstance( $iConfig, $meta );

            	$this->_prop['bbs_observation']['attachment_file'] = AppFile::createInstance( $iConfig, $iMeta );

                $this->_propertySegment['attachment_file'] = true;
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
			$errors = [];
			$iConfig = $this->_config;

			$param['bbs_observation'] =
			[
				'visibility' => $this->getVisibility(),
				'observer' => $this->getObserver(),
				'supervisor' => $this->getSupervisor(),
				'notes' => $this->getNotes(),
				'recommendation' => $this->getRecommendation(),
				'action_taken' => $this->getActionTaken(),
				'feedback_to_coworkers' => $this->getFeedbackToCoworkers(),
                'attachment_file' => null,
				'created_by' => $this->getCreatedBy(),
				'date_created' => $this->getDateCreated(),
				'deleted' => $this->isDeleted()
			];

			$iDb = $iConfig->getDbAdapter();
			$dbTransaction = $iDb->beginTransaction();

			# save the file

            if( $iAttachmentFile = $this->getAttachmentFile() )
            {
                if( $iAttachmentFile->create() )
                {
                    $param['bbs_observation']['attachment_file'] = $iAttachmentFile->getId();
                }
                else
                {
                    $dbTransaction && $iDb->rollback();
                    return false;
                }
            }

			# save the details

			$iDb->query
			("
				INSERT INTO `bbs_observation`
				(
					`visibility_fk`, `observer`, `supervisor`, `notes`, `recommendation`, `action_taken`, `feedback_to_coworkers`,
					`attachment_file`, `created_by`, `date_created`, `deleted`
				)
				VALUES
				(
					:visibility, :observer, :supervisor, :notes, :recommendation, :action_taken, :feedback_to_coworkers,
					:attachment_file, :created_by, :date_created, :deleted
				)",

				$param['bbs_observation']
			);

			$observationId = $iDb->lastInsertId();

			//
			// save the types
			//
			/*$kTypes = $this->getTypes();

			if( $kTypes )
			{
				foreach( $kTypes as $key => $typeId )
				{
					$observationKey = "observation" . $key;
					$typeKey = "type" . $key;

					$param['bbs_observation_type'][$key] =
					[
						$observationKey => $observationId,
						$typeKey => $typeId
					];

					$iDb->query
					("
						INSERT INTO `bbs_observation_types`(`observation_fk`, `type_fk`, `deleted`)
						VALUES(:{$observationKey}, :{$typeKey}, 0)",

						$param['bbs_observation_type'][$key]
					);
				}
			}*/

			$this->_prop['bbs_observation']['id'] = $observationId;

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
		public function update( ObservationMeta $META ) : ?bool
		{
			//
			// requiring crud_method, id and created_by is essential in validating other observation properties
			//
			$updateRequirements =
			[
				"crud_method",
				[
					"bbs_observation", "id", "created_by"
				]
			];

			# crud_method must be update
			if( $META->require(...$updateRequirements) && $META->crud_method === ObservationMeta::CRUD_METHOD_UPDATE )
			{
				# id must be equals the value during the class construction
				$observation = $META->bbs_observation;
				$observationId = $observation['id'] ?? null;

				if( $this->getId() !== $observationId )
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
	    	$allowedProperties['bbs_observation'] =
	    	[
				[ "observer", "observer"],
				[ "supervisor", "supervisor"],
				[ "notes", "notes"],
				[ "recommendation", "recommendation"],
				[ "action_taken", "action_taken"],
				[ "feedback_to_coworkers", "feedback_to_coworkers"],
                [ "attachment_file", "attachment_file" ]
	    	];

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
                        $paramVal = $segmentProp[$name];

                        switch($name)
                        {
                            case 'attachment_file':
                                if( $paramVal )
                                {
                                    break;
                                }
                            
                            default:
                                $param[$segment][$name] = $paramVal;
                                $fieldSetQry[$segment][] = "`{$column}` = :{$name}";
                                break;
                        }
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

			# save the file changes
			if( $iAttachmentFile = $META->bbs_observation['attachment_file'] ?? null )
			{
				if( $iAttachmentFile->create() )
				{
					$param['bbs_observation']['attachment_file'] = $iAttachmentFile->getId();
					$fieldSetQry['bbs_observation'][] = "`attachment_file` = :attachment_file";
				}
				else
				{
					$dbTransaction && $iDb->rollback();
					return false;
				}

				$this->_propertySegment['attachment_file'] = false;
			}

	    	# save the detail changes
	    	if( $param['bbs_observation'] ?? null )
	    	{
	    		$param['bbs_observation']['uid'] = $this->getId();
	    		$param['bbs_observation']['created_by'] = $this->getCreatedBy();

	    		$BBSObservationSetQry = implode(", ", $fieldSetQry['bbs_observation']);

				$iDb->query
				("
					UPDATE `bbs_observation` SET {$BBSObservationSetQry}
					WHERE `uid` = :uid AND `created_by` = :created_by",

					$param["bbs_observation"]
				);

				$this->_propertySegment['bbs_observation'] = false;
	    	}

	    	$dbTransaction && $iDb->commit();

	    	return true;
		}
	}

?>