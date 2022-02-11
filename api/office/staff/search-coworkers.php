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
	require_once "api/office/staff/registered-office-staff-config.php";

	use Core\API\PaginationIndex,
		Core\API\PaginationOrderBy,
		Core\API\PaginationFilterFactory,
		App\Office\Staff\Search\StaffSearchColumn,
		App\Office\Staff\Search\StaffSearch,
		App\Office\Staff\Search\StaffSearchMeta;





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
		'limit' => 10,
		'filter' => $param['filter'] ?? [],
		'pagetoken' => $pagetoken = $param['pagetoken'] ?? null
	];

	$searchMeta = StaffSearchMeta::searchAndRemove($searchMeta, null);

	//
	// pagination search index
	//
	$iIdColumn = new StaffSearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null, PaginationOrderBy::_DESC );

	//
	// group by
	//
	$searchMeta['group_by'][] = $iIdColumn;

	//
	// order by
	//
	$searchMeta['order_by'][] = new PaginationOrderBy( $iIdColumn, PaginationOrderBy::_DESC );

	//
	// filters
	//

	//
	// include default filters
	//
	$defaultFilters =
	[
		[
			'name' => "created_by",
			'arithmetic_operator' => "=",
			'logic_operator' => "AND",
			'value' => $iOFCStaff->getAdmin()
		],
		[
			'name' => "id",
			'arithmetic_operator' => "!=",
			'logic_operator' => "AND",
			'value' => $iOFCStaff->getId()
		]
	];

	PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

	//
	// filter validation
	//
	$iFilterFactory = new PaginationFilterFactory(StaffSearchColumn::t_utilOps_classWithBackslash());
	$kFilter = $iFilterFactory->createFromArray($searchMeta['filter'], $filterCTORErrors);

	if( $filterCTORErrors )
	{
		$iRestResponse->addError($filterCTORErrors, "filter");
	}
	else
	{
		$searchMeta['filter'] = $kFilter;	
	}

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