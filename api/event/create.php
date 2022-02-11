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
        'crud_method' => EventMeta::CRUD_METHOD_CREATE,
        'event' =>
        [
			'title' => @$event['title'],
			'description' => @$event['description'],
			'location' => @$event['location'],
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
	if( false === $iRestResponse->hasError() && $iEvent ?? null )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iRestResponse->data['result']['event_id'] = $iEvent->create();

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>