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

	use App\SupportTicket\Ticket,
		App\SupportTicket\TicketMeta;





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
	$ticket = $post['param']['ticket'] ?? [];

	$propertiesForUpdate =
	[
		'category' => @$ticket['category'],
		'description' => @$ticket['description'],
		'severity' => @$ticket['severity'],
		'status' =>  @$ticket['status'],
		'deleted' => is_null(@$ticket['deleted'] ?? null) ? null : !!$ticket['deleted']
	];	

	$propertiesForUpdate = TicketMeta::searchAndRemove( $propertiesForUpdate, null );

    $ticketMeta =
    [
        'crud_method' => TicketMeta::CRUD_METHOD_UPDATE,
        'ticket' =>
        [
        	'id' => @$ticket['id'],
			'created_by' => $iAccount

        ] + $propertiesForUpdate
    ];

    $iTicketMeta = TicketMeta::createInstance( $iConfig, $ticketMeta );

	if( $iTicketMeta instanceof TicketMeta )
	{
		$iTicket = Ticket::createInstance( $iConfig, $iTicketMeta );
	}
	else
	{
		$iRestResponse->addError( $iTicketMeta );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && isset($iTicket) )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		if( $iTicket->update($iTicketMeta) )
		{
			$iRestResponse->data['result']['event'] =
			[
				'id' => $iTicket->getId(),
				'category_id' => $iTicket->getCategory(),
				'description' => $iTicket->getDescription(),
				'severity_id' => $iTicket->getSeverity(),
				'status_id' => $iTicket->getStatus(),
				'created_by' => $iTicket->getCreatedBy(),
				'date_created' => $iTicket->getDateCreated(),
				'deleted' => $iTicket->isDeleted()
			];
		}
		else
		{
			$iRestResponse->addError("failed to update", "ticket_update_attempt");
		}

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>