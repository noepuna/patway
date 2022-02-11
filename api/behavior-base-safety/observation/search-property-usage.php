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
		Core\API\PaginationFilter,
		Core\API\PaginationFilters,
		Core\API\PaginationOrderBy,
		Core\API\PaginationFilterFactory,
		App\Construction\BehaviorBaseSafety\Property\Search\PropertyUsageSearch,
		App\Construction\BehaviorBaseSafety\Property\Search\PropertyUsageSearchMeta,
		App\Construction\BehaviorBaseSafety\Property\Search\PropertyUsageSearchColumn;





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

	$searchMeta = PropertyUsageSearchMeta::searchAndRemove($searchMeta, null);

	if( $pagetoken )
	{
		# auto remove the filter arguments if pagetoken is given 

		unset($searchMeta['filter']);
	}
	else
	{
		# pagination search index

		$iPropertyIdColumn = new PropertyUsageSearchColumn("property_id");

		$searchMeta['index_column'] = new PaginationIndex( $iPropertyIdColumn, null);

		# group by

		$searchMeta['group_by'][] = new PropertyUsageSearchColumn("property_id");
		$searchMeta['group_by'][] = new PropertyUsageSearchColumn("property_value");

		# order by

		//$searchMeta['order_by'][] = new PaginationOrderBy( $iPropertyIdColumn, PaginationOrderBy::_DESC );

		# filters

		# include default filters

		$defaultFilters =
		[
			[
				'name' => "office",
				'arithmetic_operator' => "=",
				'logic_operator' => "AND",
				'value' => $iOFCStaff->getOffice()
			]
		];

		PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

		# filter validation

		$iFilterFactory = new PaginationFilterFactory(PropertyUsageSearchColumn::t_utilOps_classWithBackslash());
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

	$iMeta = PropertyUsageSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof PropertyUsageSearchMeta )
	{
		$iPagination = PropertyUsageSearch::createInstance( $iConfig, $iMeta );
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
		$data = [];

		foreach( $iPagination->getResult()['data'] as $row )
		{
			$propertyId = $row['property_id'];

			if( !($data[ $propertyId ] ?? null) )
			{
				$data[ $propertyId ] =
				[
					'category' => $row['category_id'],
					'id' => $row['property_id'],
					'name' => $row['property_name'],
					'deleted' => $row['property_deleted']
				];
			}

			$valueName = $row['property_value'];

			if( !($data[$propertyId]['values'][$valueName] ?? null) )
			{
				$data[$propertyId]['values'][$valueName] = $row['value_count'];
			}
		}

		$iRestResponse->data['result']['data'] = array_values($data);
	}

	echo $iRestResponse->build("json");

?>