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

	$propertiesForUpdate =
	[
		'title' => @$event['title'],
		'description' => @$event['description'],
		'location' => @$event['location'],
		'closed' => is_null(@$event['closed'] ?? null) ? null : !!$event['closed'],
		'deleted' => is_null(@$event['deleted'] ?? null) ? null : !!$event['deleted']
	];	

	$propertiesForUpdate = EventMeta::searchAndRemove( $propertiesForUpdate, null );

    $eventMeta =
    [
        'crud_method' => EventMeta::CRUD_METHOD_UPDATE,
        'event' =>
        [
        	'id' => @$event['id'],
			'created_by' => $iAccount

        ] + $propertiesForUpdate
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
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		if( $iEvent->update($iEventMeta) )
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
		}
		else
		{
			$iRestResponse->addError("failed to update", "event_update_attempt");
		}

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>