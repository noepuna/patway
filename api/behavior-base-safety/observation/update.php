<?php

	/**
	 * API Method Description:
	 *
	 *	...
	 *
	 *	@param ... 	...  ...
	 * 	@param ... 	...  ...
	 *	@param ... 	...  ...
	 *	@param ... 	...  ...
	 *  @param ... 	...  ...
	 *
	 *	@return  ...  ...  ...
 	 */
 
	/*
	 * Enumerate Dependencies.
	 */
	require_once "api/account/registered-account-config.php";

	use App\File\File as AppFile,
		App\File\FileMeta as AppFileMeta,
		App\File\FileVisibilityMeta,
		App\Structure\ComponentMeta as AppComponentMeta,
		App\Structure\Component as AppComponent,
		App\BehaviorBaseSafety\Observation,
		App\BehaviorBaseSafety\ObservationMeta,
		App\BehaviorBaseSafety\Property\Property,
		App\BehaviorBaseSafety\Property\PropertyMeta;





	/*
	 * API Authorization
	 *
	 * ...
	 */
	$iConfig 		= new App\Config;
	$iRestResponse 	= new Resource\APIResponse();





	/*
	 *	API Request Validations.
	 *
	 * ...
	 *
	 */
	$iPost = $iServer->getPostRequest();
	$iFiles = $iPost->getFileUpload()['param'] ?? [];
	$observation = $post['param']['bbs_observation'] ?? [];

	# save the new file

	if( $iFiles['bbs_observation']['attachment_file'] ?? null )
	{
	    # provide the app component for the app file
		$componentMeta =
		[
			'crud_method' => AppComponentMeta::CRUD_METHOD_READ,
			'app_component' =>
			[
				'id' => 4
			]
		];

		$iAppComponentMeta = AppComponentMeta::createInstance( $iConfig, $componentMeta );

	    # validate file attachment
		$uploadErrKeys = [ "bbs_observation", "attachment_file_upload" ];

		if( $iAppComponentMeta instanceof AppComponentMeta )
		{
			if( $iAppComponent = AppComponent::createInstance( $iConfig, $iAppComponentMeta ) )
			{
				$fileMeta =
				[
					'crud_method' => AppFileMeta::CRUD_METHOD_CREATE,
					'app_file' =>
					[
						'app_component' => $iAppComponent,
						'file_upload' => $iFiles['bbs_observation']['attachment_file'] ?? null,
						'title' => null,
						'description' => null,
						'visibility' => FileVisibilityMeta::TYPE_PUBLIC,
						'created_by' => $iAccount
					]
				];

				$iFileMeta = AppFileMeta::createInstance( $iConfig, $fileMeta );

				if( $iFileMeta instanceof AppFileMeta )
				{
					$iFile = AppFile::createInstance( $iConfig, $iFileMeta );
				}
				else
				{
					$iRestResponse->addError( $iFileMeta['app_file'], ...$uploadErrKeys );
				}
			}
			else
			{
				$iRestResponse->addError( "app component not ready", ...array_merge($uploadErrKeys, ["app_component"]) );
			}
		}
		else
		{
			$iRestResponse->addError( $iAppComponentMeta['app_component']['id'] ?? "error not found", ...array_merge($uploadErrKeys, ["app_component"]) );
		}
	}

	# validate observation meta

	$observationMeta =
    [
        'crud_method' => ObservationMeta::CRUD_METHOD_UPDATE,
        'bbs_observation' =>
        [
        	'id' => $observation['id'] ?? 12,
        	'types' => $observation['types'] ?? null,
			'observer' => $observation['observer'] ?? null,
			'supervisor' => $observation['supervisor'] ?? null,
			'notes' => $observation['notes'] ?? null,
			'recommendation' => $observation['recommendation'] ?? null,
			'action_taken' => $observation['action_taken'] ?? null,
			'feedback_to_coworkers' => $observation['feedback_to_coworkers'] ?? null,
			'created_by' => $iAccount ?? null,
			'attachment_file' => $iFile ?? null
        ]
    ];

    # observation meta validation

	$observationMeta = ObservationMeta::searchAndRemove( $observationMeta, null );

	# secure the nullable parameters

	if( !($iFile ?? null) && ($observation['attachment_file'] ?? null) === "0" )
	{
		$observationMeta['bbs_observation']['attachment_file'] = null;
	}

	# secure the construct requirements for observation instance

	$observationMeta['bbs_observation'] += [ 'id' => $observationMeta['id'] ?? null, 'created_by' => $iAccount ?? null ];

	//
	// settings must be booleans ---> change this into "delete must be booleans" when delete feature will be implemented
	//
	//$meta['settings'] = array_map(fn($data) => !!$data, $meta['settings']);

	$iObservation = null;
    $iObservationMeta = ObservationMeta::createInstance( $iConfig, $observationMeta );

    if( $iObservationMeta instanceof ObservationMeta )
    {
    	$iObservation = Observation::createInstance( $iConfig, $iObservationMeta );
    }
    else
    {
    	$iRestResponse->addError( $iObservationMeta );
    }

    # map posted property values into an observation property class instance
	$ikPropMeta = [];

    $propertyEvt = function( $entry, $key ) use ( $iConfig, $iRestResponse, $iObservation, &$ikPropMeta )
    {
    	$propErrKeys = [ "bbs_observation", "properties" ];

    	$propMeta =
    	[
    		'crud_method' => PropertyMeta::CRUD_METHOD_UPDATE,
    		'bbs_observation_property' =>
    		[
    			'observation' => $iObservation,
	    		'id' => $entry['id'] ?? null,
	    		'value' => $entry['value'] ?? null,
	    		'count' => $entry['count'] ?? null,
	    		'deleted' => !!($entry['deleted'] ?? false)
    		]
    	];

    	$iPropMeta = PropertyMeta::createInstance( $iConfig, PropertyMeta::searchAndRemove($propMeta, null) );

    	if( $iPropMeta instanceof PropertyMeta )
    	{
    		$ikPropMeta[$key] = $iPropMeta;
    	}
    	else
    	{
    		$iRestResponse->addError( $iPropMeta['bbs_observation_property'], ...array_merge($propErrKeys, [strval($key)]) );
    	}
    };

    $properties = $observation['properties'] ?? [];

    $observationMeta['bbs_observation']['properties'] = array_map( $propertyEvt, $properties, array_keys($properties) );





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && ( $iObservation ?? null ) )
	{
		# save the updates
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iObservation->update( $iObservationMeta );

		foreach( $ikPropMeta as $key => $iPropMeta )
		{
			if( $iProp = Property::createInstance($iConfig, $iPropMeta) )
			{
				$iProp->update($iPropMeta);
			}
			else
			{
				$iRestResponse->addError( "property not ready", "bbs_observation", "properties", strval($key) );
			}
		}

		$dbTransaction && ( $iRestResponse->hasError() ? $iDb->rollback() : $iDb->commit() );

		# return the updated record
		$iRestResponse->data['result']['bbs_observation'] =
		[
			'id' => $iObservation->getId(),
			'types' => $iObservation->getTypes(),
			'observer' => [ 'id' => $iObservation->getObserver() ],
			'supervisor' => [ 'id' => $iObservation->getSupervisor() ],
			'notes' => $iObservation->getNotes(),
			'recommendation' => $iObservation->getRecommendation(),
			'action_taken' => $iObservation->getActionTaken(),
			'feedback_to_coworkers' => $iObservation->getFeedbackToCoworkers(),
			'attachment_file' => null,
			'created_by' => $iObservation->getCreatedBy(),
			'date_created' => $iObservation->getDateCreated(),
			'deleted' => $iObservation->isDeleted()
		];

		if( $iAttachment = $iObservation->getAttachmentFile() )
		{
			$iRestResponse->data['result']['bbs_observation']['attachment_file'] = $iAttachment->getUrlPath();
		}

		//this is a temporary code. you can do better
		if( $observer = $iAccount::t_AccountOps_getInfoById($iConfig, $iObservation->getObserver()) )
		{
			$firstname = $observer['firstname'] ?? "";
			$lastname = $observer['lastname'] ?? "";

			$iRestResponse->data['result']['bbs_observation']['observer'] =
			[
				'id' => $iObservation->getObserver(),
				'name' => ($firstname ? $firstname . "\x20" : "\0") . $lastname,
				'email' => $observer['email'] ?? null
			];
		}

		if( $supervisor = $iAccount::t_AccountOps_getInfoById($iConfig, $iObservation->getSupervisor()) )
		{
			$firstname = $supervisor['firstname'] ?? "";
			$lastname = $supervisor['lastname'] ?? "";

			$iRestResponse->data['result']['bbs_observation']['supervisor'] =
			[
				'id' => $iObservation->getSupervisor(),
				'name' => ($firstname ? $firstname . "\x20" : "\0") . $lastname,
				'email' => $supervisor['email'] ?? null
			];
		}

		//end of temporary code

		$mapFn = function( $iProperty )
		{
			return
			[
				'id' => $iProperty->getId(),
				'value' => $iProperty->getValue()
			];
		};

		$iRestResponse->data['result']['bbs_observation']['properties'] = array_map( $mapFn, $iObservation->getProperties() );
	}

	echo $iRestResponse->build("json");

?>