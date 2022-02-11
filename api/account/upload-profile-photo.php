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
		App\Structure\Component as AppComponent;





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

    //
    // provide the app component for the app file
    //
	$componentMeta =
	[
		'crud_method' => AppComponentMeta::CRUD_METHOD_READ,
		'app_component' =>
		[
			'id' => 500
		]
	];

	$iAppComponentMeta = AppComponentMeta::createInstance( $iConfig, $componentMeta );
	//
    // ready the attachment file
    //
	$uploadErrKeys = [ "account", "profile_photo" ];

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
					'file_upload' => $iFiles['account']['profile_photo'] ?? null,
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

			//
			// temporary code here
			//
			$allowedTypes = [ "image/jpeg", AppFileMeta::_EXTN['png'] ];

			if( !in_array($fileMeta['app_file']['file_upload']->getType(), $allowedTypes) )
			{
				$iRestResponse->addError( "invalid type", ...array_merge($uploadErrKeys, ["file_upload"])  );
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





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iFile ?? null )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iRestResponse->data['result']['account']['profile_photo'] = $iFile->create();

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>