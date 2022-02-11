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
		Core\API\PaginationOrderBy,
		Core\API\PaginationFilterFactory,
		App\Messaging\HashTag\Search\Search,
		App\Messaging\HashTag\Search\SearchMeta,
		App\Messaging\HashTag\Search\SearchColumn;





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
		'filter' => $filters = $param['filter'] ?? [],
		'pagetoken' => $pagetoken = $param['pagetoken'] ?? null
	];

	$searchMeta = SearchMeta::searchAndRemove($searchMeta, null);

	if( $pagetoken )
	{
		# auto remove the filter arguments if pagetoken is given 

		unset($searchMeta['filter']);
	}
	else
	{
		# pagination search index

		$iIdColumn = new SearchColumn("id");

		$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

		# group by
		
		# noop

		# order by

		$iHashTagNameColumn = new SearchColumn("hash_tag");

		$searchMeta['order_by'][] = new PaginationOrderBy( $iHashTagNameColumn, PaginationOrderBy::_DESC );

		# filters

		# include default filters

		$defaultFilters =
		[
			[
				"logic_operator" => "AND",
				"value" =>
				[
					[ 'name' => "sender", 'value' => $iAccount->getId(), 'arithmetic_operator' => "=", 'logic_operator' => "AND" ],
					[ 'name' => "recipient", 'value' => $iAccount->getId(), 'arithmetic_operator' => "=", 'logic_operator' => "OR" ],
				]
			],
			[
				'name' => "deleted",
				'arithmetic_operator' => "=",
				'logic_operator' => "AND",
				'value' => 0
			]
		];

		PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

		# filter validation

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
	}

	# create search meta

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
				'msg_id' => $data['id'],
				'hashtag' => $data['hash_tag_name']
			];

		};

		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iPagination->getResult()['data'] );
	}

	echo $iRestResponse->build("json");

?>