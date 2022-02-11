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

	use App\Event\Event,
		App\Event\EventMeta;





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
	$event = $post['param']['event'] ?? [];

    $eventMeta =
    [
        'crud_method' => EventMeta::CRUD_METHOD_READ,
        'event' =>
        [
        	'id' => $event['id'] ?? null,
        	'created_by' => $iAccount
        ]
    ];

    $iEventMeta = EventMeta::createInstance( $iConfig, $eventMeta );

	if( $iEventMeta instanceof EventMeta )
	{
		$iEvent = Event::createInstance( $iConfig, $iEventMeta );
	}
	else
	{
		$iRestResponse->addError( $iEventMeta );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iEvent )
	{
		$iRestResponse->data['result']['event'] =
		[
			'id' => $iEvent->getId(),
			'title' => $iEvent->getTitle(),
			'description' => $iEvent->getDescription(),
			'location' => $iEvent->getLocation(),
			'closed' => $iEvent->isClosed(),
			'created_by' => $iEvent->getCreatedBy(),
			'date_created' => $iEvent->getDateCreated(),
			'deleted' => $iEvent->isDeleted()
		];

		//this is a temporary code. you can do better
		if( $creator = $iAccount::t_AccountOps_getInfoById($iConfig, $iEvent->getCreatedBy()) )
		{
			$firstname = $creator['firstname'] ?? "";
			$lastname = $creator['lastname'] ?? "";

			$iRestResponse->data['result']['event']['created_by'] =
			[
				'id' => $iEvent->getCreatedBy(),
				'name' => ($firstname ? $firstname . "\x20" : "\0") . $lastname,
				'email' => $creator['email'] ?? null
			];
		}

		//end of temporary code
	}

	echo $iRestResponse->build("json");

?>