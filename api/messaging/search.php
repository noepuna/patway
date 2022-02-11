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
		App\Messaging\Search\Search,
		App\Messaging\Search\SearchMeta,
		App\Messaging\Search\SearchColumn;





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
		'limit' => 2,
		'filter' => $param['filter'] ?? [],
		'pagetoken' => $param['pagetoken'] ?? null
	];

	$searchMeta = SearchMeta::searchAndRemove($searchMeta, null);

	if( !($searchMeta['pagetoken'] ?? null) )
	{
		//
		// pagination search index
		//
		$iDateCreatedColumn = new SearchColumn("date_created");

		$searchMeta['index_column'] = new PaginationIndex( $iDateCreatedColumn, null);

		//
		// group by
		//
		$searchMeta['group_by'][] = new SearchColumn("id");

		//
		// filters
		//

		# remove unexpected filters
		$unexpectedFilterFn = function( $filter )
		{
			$allowedFilters = [ "sender", "single_recipient" ];

			return in_array($filter['name'] ?? null, $allowedFilters) ? false : true;
		};

		$searchMeta['filter'] = array_filter($searchMeta['filter'], $unexpectedFilterFn);

		#include default filters
		$defaultFilters =
		[
			[
				"logic_operator" => "AND",
				"value" =>
				[
					[ 'name' => "sender", 'value' => $iAccount->getId(), 'arithmetic_operator' => "=", 'logic_operator' => "AND" ],
					[ 'name' => "single_recipient", 'value' => $iAccount->getId(), 'arithmetic_operator' => "=", 'logic_operator' => "OR" ]
				]
			]
		];

		$kDefaultFilters = new PaginationFilters("OR");

		$createFilterInstance = function( $filter )
		{
			$arithmetic = $filter['arithmetic_operator'] ?? "=";
			$logic = $filter['logic_operator'] ?? "AND";
			$value = $filter['value'] ?? null;
			$iColumn = new SearchColumn($filter['name'] ?? "");

			return new PaginationFilter( $iColumn, $value, $arithmetic, $logic );
		};

		$mapFn = function( $filter ) use (&$mapFn, $createFilterInstance)
		{
			if( !($filter['value'] ?? null) )
			{
				return;
			}

			if( $filter['name'] ?? null )
			{
				try
				{
					$return = $createFilterInstance($filter);
				}
				catch( Exception $e )
				{
					$iRestResponse->addError($e->getMessage(), "filter", $key);
				}
			}
			else if( $logic = $filter['logic_operator'] )
			{
				$return = new PaginationFilters($logic);

				$compoundFilter = array_map($mapFn, $filter['value']);

				foreach( $compoundFilter as $key => $iFilter )
				{
					$return[$key] = $iFilter;
				}
			}

			return $return;
		};

		$searchMeta['filter'] = array_merge( $searchMeta['filter'], $defaultFilters );
		$searchMeta['filter'] = array_map($mapFn, $searchMeta['filter']);
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
				'message' => $data['message'],
				'sender' =>
				[
					'id' =>   $data['sender_id'],
					'name' => $data['sender_name']
				],
				'date_created' => $data['date_created'],
				'date_updated' => $data['date_updated'],
				'deleted' => $data['deleted']
			];

		};

		$iRestResponse->data['result'] = $iPagination->getResult();
		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iRestResponse->data['result']['data'] );
	}

	echo $iRestResponse->build("json");

?>