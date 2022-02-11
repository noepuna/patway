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
		App\BehaviorBaseSafety\Property\PropertyMeta,

		Core\API\PaginationIndex,
		Core\API\PaginationFilter,
		Core\API\PaginationFilterFactory,
		App\BehaviorBaseSafety\Property\Search\ObservationPropertySearch AS Search,
		App\BehaviorBaseSafety\Property\Search\ObservationPropertySearchMeta AS SearchMeta,
		App\BehaviorBaseSafety\Property\Search\ObservationPropertySearchColumn AS SearchColumn;





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

	if( $attachmentUpload = $iFiles['bbs_observation']['attachment_file'] ?? null )
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

	    # ready the attachment file

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
						'file_upload' => $attachmentUpload,
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

	$obseravationMeta =
    [
        'crud_method' => ObservationMeta::CRUD_METHOD_CREATE,
        'bbs_observation' =>
        [
        	'visibility' => $observation['visibility'] ?? 1,
			'observer' => $iAccount->getId(),
			'supervisor' => $observation['supervisor'] ?? null,
			'notes' => $observation['notes'] ?? null,
			'recommendation' => $observation['recommendation'] ?? null,
			'action_taken' => $observation['action_taken'] ?? null,
			'feedback_to_coworkers' => $observation['feedback_to_coworkers'] ?? null,
			'created_by' => $iAccount,
			'attachment_file' => $iFile ?? null,
        ]
    ];

    $iObservationMeta = ObservationMeta::createInstance( $iConfig, $obseravationMeta );

    if( $iObservationMeta instanceof ObservationMeta )
    {
    	$iObservation = Observation::createInstance( $iConfig, $iObservationMeta );
    }
    else
    {
    	$iRestResponse->addError( $iObservationMeta );
    }

    # validate types

    $ikTypeMeta = [];
    $types = $observation['types'] ?? [];

    foreach( $types as $key => $property )
    {
    	$propMeta =
    	[
    		'crud_method' => PropertyMeta::CRUD_METHOD_READ,
    		'bbs_observation_property' =>
    		[
	    		'id' => $property['id'] ?? null,
	    		'value' => ( $property['value'] ?? null ) ? 1 : null
    		]
    	];

    	$iPropMeta = PropertyMeta::createInstance( $iConfig, $propMeta );

    	if( $iPropMeta instanceof PropertyMeta )
    	{
    		$ikTypeMeta[$key] = $iPropMeta;
    	}
    	else
    	{
    		$iRestResponse->addError( $iPropMeta['bbs_observation_property'], "bbs_observation", "types", strval($key) );
    	}
    }

    if( !count( array_filter($ikTypeMeta, fn($iMeta) => $iMeta->bbs_observation_property['value']) ) )
    {
    	$iRestResponse->addError( "atleast a type is required", "bbs_observation", "type_count" );
    }

    //
    // validate properties
    //
    $ikPropMeta = [];
    $properties = $observation['properties'] ?? [];

	$searchMeta =
	[
		'limit' => 100,
		'filter' => $param['filter'] ?? []
	];

	# pagination search index

	$iIdColumn = new SearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

	# group by

	$searchMeta['group_by'][] = $iIdColumn;

	# filters

	$searchMeta['filter'][] =
	[
		'name' => "category",
		'arithmetic_operator' => "!=",
		'logic_operator' => "AND",
		'value' => 5
	];

	PaginationFilterFactory::overrideData($searchMeta['filter'], []);

	# filter validation

	$iFilterFactory = new PaginationFilterFactory( SearchColumn::t_utilOps_classWithBackslash() );
	$kFilter = $iFilterFactory->createFromArray($searchMeta['filter'], $filterCTORErrors);

	if( $filterCTORErrors )
	{
		$iRestResponse->addError($filterCTORErrors, "filter");
	}
	else
	{
		$searchMeta['filter'] = $kFilter;	
	}

	# create search meta

    $iSearchMeta = SearchMeta::createInstance( $iConfig, $searchMeta );

	# require missing properties

	$givenProperties = array_filter( $properties, fn( $entry ) => $entry['id'] ?? false );
	$givenProperties = array_map( fn( $entry ) => $entry['id'], $givenProperties );

	if( $iSearchMeta instanceof SearchMeta )
	{
		$iPagination = Search::createInstance( $iConfig, $iSearchMeta );

		if( $iPagination && ( $propertySearch = $iPagination->getResult()['data'] ?? null ) )
		{
			foreach( $propertySearch as $property )
			{
				if( !in_array($property['id'], $givenProperties) )
				{
					$properties[] = [ 'id' => $property['id'], 'value' => null ];
				}
			}
		}
		else
		{
			$iRestResponse->addError( "properties not ready", "bbs_observation", "properties" );
		}
	}
	else
	{
		$iRestResponse->addError($iSearchMeta);
	}

	# check for required properties here ( this should be the right implementation but for now remove only null properties )

	$properties = array_filter( $properties, fn( $entry ) => $entry['value'] ?? null );

	# bbs property factory

    foreach( $properties as $key => $property )
    {
    	$propMeta =
    	[
    		'crud_method' => PropertyMeta::CRUD_METHOD_READ,
    		'bbs_observation_property' =>
    		[
	    		'id' => $property['id'] ?? null,
	    		'value' => $property['value'] ?? null
    		]
    	];

    	$iPropMeta = PropertyMeta::createInstance( $iConfig, $propMeta );

    	if( $iPropMeta instanceof PropertyMeta )
    	{
    		$ikPropMeta[$key] = $iPropMeta;
    	}
    	else
    	{
    		$iRestResponse->addError( $iPropMeta['bbs_observation_property'], "bbs_observation", "properties", strval($key) );
    	}
    }





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iObservation ?? null )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		if( $iRestResponse->data['result']['observation_id'] = $iObservation->create() )
		{
			foreach( array_merge($ikPropMeta, $ikTypeMeta) as $key => $iPropMeta )
			{
				$iPropMeta->bbs_observation_property = [ 'observation' => $iObservation ];

				if( $error = $iPropMeta->getErrors() )
				{
					$errorArgs =
					[
						$error['bbs_observation_property']['observation'] ?? "error not found",
						"bbs_observation",
						"properties",
						strval($key)
					];

					$iRestResponse->addError( ...$errorArgs );
				}
				else if( $iProp = Property::createInstance( $iConfig, $iPropMeta ) )
				{
					$iProp->create();
				}
			}

			$dbTransaction && $iDb->commit();
		}
	}

	echo $iRestResponse->build("json");

?>