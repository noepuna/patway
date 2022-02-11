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
		App\BehaviorBaseSafety\Search\ObservationSearch,
		App\BehaviorBaseSafety\Search\ObservationSearchMeta,
		App\BehaviorBaseSafety\Search\ObservationSearchColumn;





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

	$searchMeta = ObservationSearchMeta::searchAndRemove($searchMeta, null);

	if( $pagetoken )
	{
		# auto remove the filter arguments if pagetoken is given 

		unset($searchMeta['filter']);
	}
	else
	{
		# pagination search index

		$iIdColumn = new ObservationSearchColumn("id");

		$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

		# group by
		
		$searchMeta['group_by'][] = new ObservationSearchColumn("id");

		# order by

		$iDateCreatedColumn = new ObservationSearchColumn("date_created");

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
						'name' => "created_by",
						'arithmetic_operator' => "=",
						'logic_operator' => "AND",
						'value' => $iAccount->getId()
					],
					[
						'name' => "observer",
						'arithmetic_operator' => "=",
						'logic_operator' => "OR",
						'value' => $iAccount->getId()
					],
					[
						'name' => "supervisor",
						'arithmetic_operator' => "=",
						'logic_operator' => "OR",
						'value' => $iAccount->getId()
					]
				]
			],
			[
				'logic_operator' => "OR",
				'value' =>
				[
					[
						'name' => "visibility",
						'arithmetic_operator' => "=",
						'logic_operator' => "AND",
						'value' => 1
					],
					[
						'name' => "co_worker",
						'arithmetic_operator' => "=",
						'logic_operator' => "AND",
						'value' => $iAccount->getId()
					]
				]
			]
		];

		PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

		# filter validation

		$iFilterFactory = new PaginationFilterFactory(ObservationSearchColumn::t_utilOps_classWithBackslash());
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

	$iMeta = ObservationSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof ObservationSearchMeta )
	{
		$iPagination = ObservationSearch::createInstance( $iConfig, $iMeta );
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
				'action_taken' => $data['action_taken'],
				'feedback_to_coworkers' => $data['feedback_to_coworkers'],
				'recommendation' => $data['recommendation'],
				'notes' => $data['notes'],
				'supervisor' => $data['supervisor'],
				'observer' => $data['observer'],
				'owner' =>
				[
					'id' => $data['owner_id'],
					'name' => $data['owner_name']
				],
				'date_created' => $data['date_created'],
				'deleted' => $data['deleted']
			];

		};

		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iPagination->getResult()['data'] );
	}

	echo $iRestResponse->build("json");

?>