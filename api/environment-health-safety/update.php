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

	use App\Messaging\Recipient,
		App\Construction\EnvironmentHealthSafety\EHS,
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
	$ehs = $post['param']['ehs'] ?? [];
	$settings = $post['param']['settings'] ?? [];

	$meta =
	[
        'crud_method' => EHSMeta::CRUD_METHOD_UPDATE,
        'ehs' =>
        [
        	'id' => $ehs['id'] ?? null,
        	'name' => $ehs['name'] ?? null,
        	'description' => $ehs['description'] ?? null,
			'created_by' => $iOFCAdmin
        ],
        'settings' =>
        [
        	'has_event' => $settings['has_event'] ?? null
        ]
	];

    //
    // ehs message validation
    //
	$meta = EHSMeta::searchAndRemove( $meta, null );

	//
	// add ehs in meta
	//
	$meta['ehs'] += [ 'id' => $ehs['id'] ?? null, 'created_by' => $iOFCAdmin ];

	//
	// settings must be booleans
	//
	$meta['settings'] = array_map(fn($data) => !!$data, $meta['settings']);

    $iMeta = EHSMeta::createInstance( $iConfig, $meta );

    if( $iMeta instanceof EHSMeta )
    {
    	$iEHS = EHS::createInstance( $iConfig, $iMeta );
    }
    else
    {
    	$iRestResponse->addError( $iMeta );
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

		$iEHS->update( $iMeta );

		$iRestResponse->data['result']['ehs'] =
		[
			'id' => $iEHS->getId(),
			'name' => $iEHS->getName(),
			'description' => $iEHS->getDescription(),
			'icon' => $iEHS->getIconFile()->getUrlPath(),
			'attachment' => $iEHS->getAttachmentFile()->getUrlPath(),
			'date_created' => $iEHS->getDateCreated(),
			'enabled' => $iEHS->isEnabled() ? '1' : '0',
			'deleted' => $iEHS->isDeleted() ? '1' : '0',

			'settings' =>
			[
				[ 'name' => "has_event", 'enabled' => $iEHS->hasEvent() ? '1' : '0' ]
			]
		];

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>