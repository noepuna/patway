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
		Core\API\PaginationOrderBy,
		Core\API\PaginationFilterFactory,
		App\BehaviorBaseSafety\Property\Search\CategorySearch AS Search,
		App\BehaviorBaseSafety\Property\Search\CategorySearchMeta AS SearchMeta,
		App\BehaviorBaseSafety\Property\Search\CategorySearchColumn AS SearchColumn;





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
		'filter' => $param['filter'] ?? []
	];

	# pagination search index

	$iIdColumn = new SearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

	# group by

	$searchMeta['group_by'][] = $iIdColumn;

	# order by

	$searchMeta['order_by'][] = new PaginationOrderBy( $iIdColumn, PaginationOrderBy::_DESC );

	# filters

	/*$searchMeta['filter'][] =
	[
		'name' => "category",
		'arithmetic_operator' => "=",
		'logic_operator' => "AND",
		'value' => 1
	];*/

	# include default filters

	$defaultFilters = [];

	PaginationFilterFactory::overrideData($searchMeta['filter'], $defaultFilters);

	# filter validation

	$iFilterFactory = new PaginationFilterFactory( SearchColumn::t_utilOps_classWithBackslash() );
	$kFilter = $iFilterFactory->createFromArray($searchMeta['filter'], $filterCTORErrors);

	if( $filterCTORErrors )
	{
		$iRestResponse->addError($filterCTORErrors, "filter");
	}
	else
	{
		$searchMeta['filter'] = $kFilter;	
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
				'id' => $data['id'],
				'name' => $data['name']
			];
		};

		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iPagination->getResult()['data'] );
	}

	echo $iRestResponse->build("json");

?>