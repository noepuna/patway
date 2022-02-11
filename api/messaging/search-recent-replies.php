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
		App\Messaging\Search\SearchReply,
		App\Messaging\Search\SearchReplyMeta,
		App\Messaging\Search\SearchReplyColumn;





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
		'limit' => $param['limit'] ?? 10,
		'filter' => $filters = $param['filter'] ?? [],
		'pagetoken' => $param['pagetoken'] ?? null
	];

	$searchMeta = SearchReplyMeta::searchAndRemove($searchMeta, null);

	if( $searchMeta['pagetoken'] ?? null )
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
		$iMsgIdColumn = new SearchReplyColumn("id");

		$searchMeta['index_column'] = new PaginationIndex( $iMsgIdColumn, null, PaginationOrderBy::_ASC);

		//
		// group by
		//
		$searchMeta['group_by'][] = $iMsgIdColumn;

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
						'name' => "sender_id",
						'arithmetic_operator' => "=",
						'logic_operator' => "AND",
						'value' => $iAccount->getId()
					],
					[
						'name' => "recipient_id",
						'arithmetic_operator' => "=",
						'logic_operator' => "OR",
						'value' => $iAccount->getId()
					],
					[
						'name' => "conversation_owner",
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
		$iFilterFactory = new PaginationFilterFactory(SearchReplyColumn::t_utilOps_classWithBackslash());
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
	$iMeta = SearchReplyMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof SearchReplyMeta )
	{
		$iPagination = SearchReply::createInstance( $iConfig, $iMeta );
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
				'message' => $data['message'],
				'date_created' => $data['date_created'],
				'date_updated' => $data['date_updated'],
				'deleted' => $data['deleted'] == 1 || $data['deleted'] === chr(0x01),
				'sender' =>
				[
					'name' => $data['sender_name']
				]
			];

		};

		$iRestResponse->data['result'] = $iPagination->getResult();
		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iRestResponse->data['result']['data'] );
	}

	echo $iRestResponse->build("json");

?>