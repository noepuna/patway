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
	$ehs = $post['param']['ehs'] ?? [];

    $ehsMeta =
    [
        'crud_method' => EHSMeta::CRUD_METHOD_READ,
        'ehs' =>
        [
        	'id' => $ehs['id'] ?? null,
        	'co_worker' => $iAccount
        ]
    ];

    $iEHSMeta = EHSMeta::createInstance( $iConfig, $ehsMeta );

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
	if( false === $iRestResponse->hasError() && $iEHS )
	{
		//$iFile = $iEHS->getAttachmentFile();

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

		//this is a temporary code. you can do better
		if( $creator = $iAccount::t_AccountOps_getInfoById($iConfig, $iEHS->getCreatedBy()) )
		{
			$firstname = $creator['firstname'] ?? "";
			$lastname = $creator['lastname'] ?? "";

			$iRestResponse->data['result']['ehs']['created_by'] =
			[
				'id' => $iEHS->getCreatedBy(),
				'name' => ($firstname ? $firstname . "\x20" : "\0") . $lastname,
				'email' => $creator['email'] ?? null
			];
		}

		//end of temporary code
	}

	echo $iRestResponse->build("json");

?>