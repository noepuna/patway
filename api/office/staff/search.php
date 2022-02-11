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
	require_once "api/office/admin/registered-office-admin-config.php";

	use Core\API\PaginationIndex,
		Core\API\PaginationFilter,
		App\Office\Staff\Search\StaffSearch,
		App\Office\Staff\Search\StaffSearchMeta,
		App\Office\Staff\Search\StaffSearchColumn;





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
	//
	//
	$iIdColumn = new StaffSearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

	//
	// group by
	//
	# none

	//
	// filter
	//
	$iAdminColumn = new StaffSearchColumn("created_by");

	$searchMeta['filter'][] = new PaginationFilter( $iAdminColumn, $iOFCAdmin->getId(), "=", "AND" );

	//
	// create search meta
	//
	$iMeta = StaffSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof StaffSearchMeta )
	{
		$iPagination = StaffSearch::createInstance( $iConfig, $iMeta );
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