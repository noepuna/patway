<?php

	/**
	 *	API Method Description:
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

	use Core\API\PaginationIndex,
		Core\API\PaginationFilter,
		Core\API\PaginationOrderBy,
		App\SupportTicket\Search\SupportTicketSearch,
		App\SupportTicket\Search\SupportTicketSearchMeta,
		App\SupportTicket\Search\SupportTicketSearchColumn;





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
	$param = @$post['param'];

	$searchMeta =
	[
		'limit' => 10
	];

	//
	// pagination search index
	//
	$iIdColumn = new SupportTicketSearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

	//
	// group by
	//
	$searchMeta['group_by'][] = new SupportTicketSearchColumn("id");

	//
	// order by
	//
	$iDateCreatedColumn = new SupportTicketSearchColumn("date_created");

	$searchMeta['order_by'][] = new PaginationOrderBy( $iDateCreatedColumn, PaginationOrderBy::_DESC );

	//
	// filter
	//
	$iAdminColumn = new SupportTicketSearchColumn("created_by");

	$searchMeta['filter'][] = new PaginationFilter( $iAdminColumn, $iAccount->getId(), "=", "AND" );

	//
	// create search meta
	//
	$iMeta = SupportTicketSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof SupportTicketSearchMeta )
	{
		$iPagination = SupportTicketSearch::createInstance( $iConfig, $iMeta );
	}
	else
	{
		$iRestResponse->addError($iMeta);
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() )
	{
		$iRestResponse->data['result'] = $iPagination->getResult();
	}

	echo $iRestResponse->build("json");

?>