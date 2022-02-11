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
	require_once "backend/config.php";

	use App\Auth\Auth,
		App\Auth\AuthMeta,
		App\Auth\AuthPrevilegeMeta;





	/*
	 * API Authorization
	 *
	 * ...
	 */

	$iConfig 		= new App\Config;
	$iRestResponse 	= new Resource\APIResponse();
	$iHttpHeaders 	= new Core\Http\HttpHeaders($iConfig);
	$server 		= new Resource\Server();
	$post 			= $server->post;





	/*
	 *	API Request Validations.
	 *
	 * ...
	 *
	 */





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() )
	{
		//
		// return the access-token
		//
		$iRestResponse->data['result']['auth_token'] = $_COOKIE['api-access-token'] ?? null;
	}

	echo $iRestResponse->build("json");

?>