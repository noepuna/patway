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
		Core\API\PaginationFilters,
		Core\API\PaginationOrderBy,
		Core\API\PaginationFilterFactory,
		App\Office\Department\Search\Search,
		App\Office\Department\Search\SearchMeta,
		App\Office\Department\Search\SearchColumn;





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
		'limit' => $param['limit'] ?? 50,
		'filter' => $param['filter'] ?? [],
		'pagetoken' => $pagetoken = $param['pagetoken'] ?? null
	];

	$searchMeta = SearchMeta::searchAndRemove($searchMeta, null);

	//
	// pagination search index
	//
	$iDepartmentIdColumn = new SearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iDepartmentIdColumn, null, PaginationOrderBy::_ASC);

	//
	// group by
	//
	$searchMeta['group_by'][] = $iDepartmentIdColumn;

	//
	// order by
	//
	$searchMeta['order_by'][] = new PaginationOrderBy( $iDepartmentIdColumn, PaginationOrderBy::_DESC );

	//
	// filters
	//

	//
	// include default filters
	//
	$defaultFilters =
	[
		[
			"logic_operator" => "AND",
			"value" =>
			[
				[
					'name' => "admin",
					'arithmetic_operator' => "=",
					'logic_operator' => "AND",
					'value' => $iAccount->getId()
				],
				[
					'name' => "staff",
					'arithmetic_operator' => "=",
					'logic_operator' => "OR",
					'value' => $iAccount->getId()
				]
			]
		]
	];

	PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

	//
	// filter validation
	//
	$iFilterFactory = new PaginationFilterFactory(SearchColumn::t_utilOps_classWithBackslash());
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
	$iMeta = SearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof SearchMeta )
	{
		$iPagination = Search::createInstance( $iConfig, $iMeta );
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
		$mapFn = function( $data )
		{
			return
			[
				'id' => $data['id'],
				'name' => $data['name'],
				'description' => $data['description'],
				'enabled' => $data['enabled'] ? 1 : 0
			];
		};

		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iPagination->getResult()['data'] );
	}

	echo $iRestResponse->build("json");

?>