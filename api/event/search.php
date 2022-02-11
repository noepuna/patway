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

	use Core\API\PaginationFilter,
		Core\API\PaginationOrderBy,
		App\Event\Search\EventSearch,
		App\Event\Search\EventSearchMeta,
		App\Event\Search\EventSearchColumn;





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
	// group by
	//
	$searchMeta['group_by'][] = new EventSearchColumn("id");

	//
	// order by
	//
	$iDateCreatedColumn = new EventSearchColumn("date_created");

	$searchMeta['order_by'][] = new PaginationOrderBy( $iDateCreatedColumn, PaginationOrderBy::_DESC );

	//
	// filter
	//
	$iAdminColumn = new EventSearchColumn("created_by");

	$searchMeta['filter'][] = new PaginationFilter( $iAdminColumn, $iAccount->getId(), "=", "AND" );

	//
	// create search meta
	//
	$iMeta = EventSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof EventSearchMeta )
	{
		$iPagination = EventSearch::createInstance( $iConfig, $iMeta );
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