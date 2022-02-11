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
		App\Messaging\Search\SearchRecipient,
		App\Messaging\Search\SearchRecipientMeta,
		App\Messaging\Search\SearchRecipientColumn;





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
		'limit' => 100,
		'filter' => $filters = $param['filter'] ?? [],
		'pagetoken' => $pagetoken = $param['pagetoken'] ?? null
	];

	$searchMeta = SearchRecipientMeta::searchAndRemove($searchMeta, null);

	if( $pagetoken )
	{
		//
		// auto remove the filter arguments if pagetoken is given 
		//
		unset($searchMeta['filter']);
	}
	else
	{
		//
		// pagination search index
		//
		$iRecipientColumn = new SearchRecipientColumn("recipient_id");

		$searchMeta['index_column'] = new PaginationIndex( $iRecipientColumn, null);

		//
		// group by
		//
		$searchMeta['group_by'][] = $iRecipientColumn;

		//
		// filters
		//

		//
		// include default filters
		//
		$defaultFilters =
		[
			[
				'name' => "deleted",
				'arithmetic_operator' => "=",
				'logic_operator' => "AND",
				'value' => "0"
			]
		];

		PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

		//
		// filter validation
		//
		$iFilterFactory = new PaginationFilterFactory(SearchRecipientColumn::t_utilOps_classWithBackslash());
		$kFilter = $iFilterFactory->createFromArray($searchMeta['filter'], $filterCTORErrors);

		if( $filterCTORErrors )
		{
			$iRestResponse->addError($filterCTORErrors, "filter");
		}
		else
		{
			$searchMeta['filter'] = $kFilter;	
		}
	}

	//
	// create search meta
	//
	$iMeta = SearchRecipientMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof SearchRecipientMeta )
	{
		$iPagination = SearchRecipient::createInstance( $iConfig, $iMeta );
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
				'id' => $data['recipient_id'],
				'name' => $data['recipient_name'],
				'is_read' => $data['is_read'] == 1 || $data['is_read'] === chr(0x01)
			];
		};

		$iRestResponse->data['result'] = $iPagination->getResult();
		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iRestResponse->data['result']['data'] );
	}

	echo $iRestResponse->build("json");

?>