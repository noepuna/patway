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

	use App\Construction\EnvironmentHealthSafety\Messaging\SentMessage,
		App\Construction\EnvironmentHealthSafety\Messaging\SentMessageMeta;





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

    $meta =
    [
        'crud_method' => SentMessageMeta::CRUD_METHOD_READ,
        'message' =>
        [
        	'id' => $ehs['id'] ?? null,
        	'account_for_access' => $iAccount
        ]
    ];

    $iSentMessageMeta = SentMessageMeta::createInstance( $iConfig, $meta );

	if( $iSentMessageMeta instanceof SentMessageMeta )
	{
		$iSentMessage = SentMessage::createInstance( $iConfig, $iSentMessageMeta );
	}
	else
	{
		$iRestResponse->addError( $iSentMessageMeta );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iSentMessage )
	{
		//$iFile = $iEHS->getAttachmentFile();

		$iRestResponse->data['result']['sent_message'] =
		[
			'id' => $iSentMessage->getId(),
			'type' => $iSentMessage->getType(),
			//'recipients' => $iSentMessage->getRecipients(),
			//'group' => null,
			'sender' => $iSentMessage->getSender(),
			'date_created' => $iSentMessage->getDateCreated(),
			'deleted' => $iSentMessage->isDeleted() ? '1' : '0',

			'ehs' => $iSentMessage->getEHS(),
			'status' => $iSentMessage->getStatus(),
			'title' => $iSentMessage->getTitle(),
			'location' => $iSentMessage->getLocation(),
			'risk_level' => $iSentMessage->getRiskLevel(),
			'date_start' => $iSentMessage->getDateStart(),
			'date_end' => $iSentMessage->getDateEnd(),
			'description' => $iSentMessage->getDescription()
		];

		//this is a temporary code. you can do better
		if( $sender = $iAccount::t_AccountOps_getInfoById($iConfig, $iSentMessage->getSender()) )
		{
			$firstname = $sender['firstname'] ?? "";
			$lastname = $sender['lastname'] ?? "";

			$iRestResponse->data['result']['sent_message']['sender'] =
			[
				'id' => $iSentMessage->getSender(),
				'name' => ($firstname ? $firstname . "\x20" : "\0") . $lastname,
				'email' => $sender['email'] ?? null
			];
		}

		//end of temporary code
	}

	echo $iRestResponse->build("json");

?>