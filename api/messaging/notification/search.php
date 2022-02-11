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
		App\Messaging\Notification\Search\Search,
		App\Messaging\Notification\Search\SearchMeta,
		App\Messaging\Notification\Search\SearchColumn;





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
	$iNotifIdColumn = new SearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iNotifIdColumn, null, PaginationOrderBy::_ASC);

	//
	// group by
	//
	//$searchMeta['group_by'][] = new SearchColumn("recipient");
	//$searchMeta['group_by'][] = $iNotifIdColumn;

	//
	// order by
	//
	$iDateCreatedColumn = new SearchColumn("date_created");

	$searchMeta['order_by'][] = new PaginationOrderBy( $iDateCreatedColumn, PaginationOrderBy::_DESC );

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
					'name' => "recipient",
					'arithmetic_operator' => "=",
					'logic_operator' => "AND",
					'value' => $iAccount->getId()
				],
				[
					'name' => "actor",
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
				'conversation' => $data['conversation'],
				'app_component' => $data['app_component'],
				'payload' => $data['payload'],
				'created_by' =>
				[
					'id' =>   $data['sender_id'],
					'name' => $data['sender_name']
				],
				'date_created' => $data['date_created']
			];
		};

		//
		// start the long pooling
		//
		$updateCheckCount = 0;

		//session_write_close(); -- is yet to be decided
		ignore_user_abort(true);
		//set_time_limit(0); -- is yet to be decided

		while( $updateCheckCount < 20 )
		{
			$updateCheckCount++; # IMPORTANT #

			$iRestResponse->data['result'] = $iPagination->getResult();

			if( $iRestResponse->data['result']['data'] ?? null )
			{
				break;
			}

			sleep(1);
		}

		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iRestResponse->data['result']['data'] );
	}

	echo $iRestResponse->build("json");

?>