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
	require_once "api/office/admin/registered-office-admin-config.php";

	use App\File\File as AppFile,
		App\File\FileMeta as AppFileMeta,
		App\File\FileVisibilityMeta,
		App\Structure\ComponentMeta as AppComponentMeta,
		App\Structure\Component as AppComponent;

	use App\Construction\EnvironmentHealthSafety\EHS,
		App\Construction\EnvironmentHealthSafety\EHSMeta;





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
	$ehs = $post['param']['ehs'] ?? [];
	$settings = $post['param']['settings'] ?? [];

	$EHSMeta =
    [
        'crud_method' => EHSMeta::CRUD_METHOD_CREATE,
        'ehs' =>
        [
        	'name' => $ehs['name'] ?? null,
        	'description' => $ehs['description'] ?? null,
			'created_by' => $iOFCAdmin
        ],
        'settings' =>
        [
        	'has_event' => $settings['has_event'] ?? null
        ]
    ];

    $EHSMeta['settings'] = EHSMeta::searchAndRemove( $EHSMeta['settings'], null );

    //
    // provide the app component for the app file
    //
	$componentMeta =
	[
		'crud_method' => AppComponentMeta::CRUD_METHOD_READ,
		'app_component' =>
		[
			'id' => 5
		]
	];

	$iAppComponentMeta = AppComponentMeta::createInstance( $iConfig, $componentMeta );

	//
    // icon file & attachment file validations
    //
	$uploadErrKeys = [ [ "ehs", "icon_file" ], [ "ehs", "attachment_file" ] ];

	foreach( $uploadErrKeys as $key => [ $segmentKey, $fileKey ] )
	{
		$uploadErrKeys = [ $segmentKey, $fileKey ];

		//
		// file component must be enabled
		//
		if( $iAppComponentMeta instanceof AppComponentMeta )
		{
			$iAppComponent = AppComponent::createInstance( $iConfig, $iAppComponentMeta );

			if( !is_a($iAppComponent, AppComponent::t_utilOps_classWithBackslash()) )
			{
				$iRestResponse->addError( "icon upload initialization failed", ...$uploadErrKeys );
			}
		}
		else
		{
			$iRestResponse->addError( "{$fileKey} upload feature is not available", ...$uploadErrKeys );
		}

		if( $iRestResponse->toArray()['error'][$segmentKey][$fileKey] ?? null )
		{
			continue;
		}

		//
		// file upload validation
		//
		if( $iFiles['ehs'][$fileKey]['resource'] ?? null )
		{
			$fileMeta =
			[
				'crud_method' => AppFileMeta::CRUD_METHOD_CREATE,
				'app_file' =>
				[
					'app_component' => $iAppComponent,
					'file_upload' => $iFiles['ehs'][$fileKey]['resource'],
					'title' => $ehs[$fileKey]['title'] ?? null,
					'description' => $ehs[$fileKey]['description'] ?? null,
					'visibility' => FileVisibilityMeta::TYPE_PUBLIC,
					'created_by' => $iOFCAdmin
				]
			];

			$iFileMeta = AppFileMeta::createInstance( $iConfig, $fileMeta );

			if( $iFileMeta instanceof AppFileMeta )
			{
				$EHSMeta['ehs'][$fileKey] = AppFile::createInstance( $iConfig, $iFileMeta );
			}
			else if( $uploadErr = $iFileMeta['app_file']['file_upload'] ?? null )
			{
				$iRestResponse->addError( $uploadErr, ...$uploadErrKeys );
			}
			else
			{
				$iRestResponse->addError( "icon upload initialization has errors", ...$uploadErrKeys );
			}
		}
		else
		{
			$EHSMeta['ehs'][$fileKey] = null;
		}
	}

    $iEHSMeta = EHSMeta::createInstance( $iConfig, $EHSMeta );

    if( $iEHSMeta instanceof EHSMeta )
    {
    	$iEHS = EHS::createInstance( $iConfig, $iEHSMeta );
    }
    else
    {
    	$iRestResponse->addError( $iEHSMeta );
    }





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iEHS ?? null )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iRestResponse->data['result']['ehs_id'] = $iEHS->create();

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>