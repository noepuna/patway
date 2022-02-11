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

    $ticketMeta =
    [
        'crud_method' => TicketMeta::CRUD_METHOD_READ,
        'ticket' =>
        [
        	'id' => @$ticket['id'],
        	'created_by' => $iAccount
        ]
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

	echo $iRestResponse->build("json");

?>