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
		App\EnvironmentHealthSafety\Search\EHSSearch,
		App\EnvironmentHealthSafety\Search\EHSSearchMeta,
		App\EnvironmentHealthSafety\Search\EHSSearchColumn;





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
		'limit' => 10
	];

	//
	//
	//
	$iIdColumn = new EHSSearchColumn("id");

	$searchMeta['index_column'] = new PaginationIndex( $iIdColumn, null);

	//
	// group by
	//
	$searchMeta['group_by'][] = new EHSSearchColumn("id");

	//
	// order by
	//
	$iDateCreatedColumn = new EHSSearchColumn("date_created");

	$searchMeta['order_by'][] = new PaginationOrderBy( $iDateCreatedColumn, PaginationOrderBy::_DESC );

	//
	// filter
	//
	$iCreatedByColumn = new EHSSearchColumn("created_by");

	$searchMeta['filter'][] = new PaginationFilter( $iCreatedByColumn, $iAccount->getId(), "=", "AND" );

	$iCoworkerColumn = new EHSSearchColumn("co_worker");

	$searchMeta['filter'][] = new PaginationFilter( $iCoworkerColumn, $iAccount->getId(), "=", "OR" );

	//
	// create search meta
	//
	$iMeta = EHSSearchMeta::createInstance( $iConfig, $searchMeta );

	if( $iMeta instanceof EHSSearchMeta )
	{
		$iPagination = EHSSearch::createInstance( $iConfig, $iMeta );
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
				'owner' =>
				[
					'id' =>   $data['owner_id'],
					'name' => $data['owner_name']
				],
				'icon' =>
				[
					'id' => $data['icon_id'],
					'url' => $data['icon_url']
				],
				'date_created' => $data['date_created'],
				'enabled' => $data['enabled'],
				'deleted' => $data['deleted']
			];

		};

		$iRestResponse->data['result']['data'] = array_map( $mapFn, $iPagination->getResult()['data'] );
	}

	echo $iRestResponse->build("json");

?>