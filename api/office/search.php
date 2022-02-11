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
	require_once "api/system/registered-system-config.php";

	use Core\API\PaginationIndex,
		Core\API\PaginationFilter,
		App\Office\Owner\Search\OwnerSearchColumn,
		App\Office\Owner\Search\OwnerSearch,
		App\Office\Owner\Search\OwnerSearchMeta;





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
	$iIdColumn = new OwnerSearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

	//
	// group by
	//
	$searchMeta['group_by'][] = new OwnerSearchColumn("id");

	//
	// filter
	//
	//$iAdminColumn = new OwnerSearchColumn("created_by");

	//$searchMeta['filter'][] = new PaginationFilter( $iAdminColumn, $iOFCAdmin->getId(), "=", "AND" );

	//
	// create search meta
	//
	$iMeta = OwnerSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof OwnerSearchMeta )
	{
		$iPagination = OwnerSearch::createInstance( $iConfig, $iMeta );
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