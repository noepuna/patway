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
		Core\API\PaginationFilter,
		Core\API\PaginationFilterFactory,
		App\EnvironmentHealthSafety\Messaging\Search\EHSMessageSearch,
		App\EnvironmentHealthSafety\Messaging\Search\EHSMessageSearchMeta,
		App\EnvironmentHealthSafety\Messaging\Search\EHSMessageSearchColumn;





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

	$searchMeta = EHSMessageSearchMeta::searchAndRemove($searchMeta, null);

	if( $searchMeta['pagetoken'] ?? null )
	{
		# auto remove the filter arguments if pagetoken is given 

		unset($searchMeta['filter']);
	}
	else
	{
		# pagination search index

		$iMsgIdColumn = new EHSMessageSearchColumn("id");

		$searchMeta['index_column'] = new PaginationIndex( $iMsgIdColumn, null, PaginationOrderBy::_ASC);

		# group by

		$searchMeta['group_by'][] = $iMsgIdColumn;

		# order by

		$iDateCreatedColumn = new EHSMessageSearchColumn("date_created");

		$searchMeta['order_by'][] = new PaginationOrderBy( $iDateCreatedColumn, PaginationOrderBy::_DESC );

		# filters

		# include default filters

		$defaultFilters =
		[

			[
				"logic_operator" => "AND",
				"value" =>
				[
					[
						'name' => "sender",
						'arithmetic_operator' => "=",
						'logic_operator' => "AND",
						'value' => $iAccount->getId()
					],
					[
						'name' => "recipient",
						'arithmetic_operator' => "=",
						'logic_operator' => "OR",
						'value' => $iAccount->getId()
					]
				]
			]
		];

		PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

		# filter validation

		$iFilterFactory = new PaginationFilterFactory(EHSMessageSearchColumn::t_utilOps_classWithBackslash());
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

	$iMeta = EHSMessageSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof EHSMessageSearchMeta )
	{
		$iPagination = EHSMessageSearch::createInstance( $iConfig, $iMeta );
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
				'message_type' => $data['message_type'],
			    'title' => $data['title'],
			    'location' => $data['location'],
			    'description' => $data['description'],
			    'risk_level' => $data['risk_level'],
			    'date_start' => $data['date_start'],
			    'date_end' => $data['date_end'],
				'sender' =>
				[
					'id' =>   $data['sender_id'],
					'name' => $data['sender_name']
				],
				'status' => $data['status'],
				'date_created' => $data['date_created'],
				'deleted' => $data['deleted']
			];

		};

		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iPagination->getResult()['data'] );
	}

	echo $iRestResponse->build("json");

?>