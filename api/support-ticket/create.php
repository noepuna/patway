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
        'crud_method' => TicketMeta::CRUD_METHOD_CREATE,
        'ticket' =>
        [
			'category' => @$ticket['category'] ?? 1,
			'description' => @$ticket['description'],
			'severity' => @$ticket['severity'],
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
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iRestResponse->data['result']['event_id'] = $iTicket->create();

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>