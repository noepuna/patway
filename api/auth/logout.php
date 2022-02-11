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
	
	use App\Auth\Auth;





	/*
	 *	API Request Validations.
	 *
	 * ...
	 *
	 */
	# noop





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
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() )
	{
		setcookie("api-access-token", false, time()+60*60*24*30, $iConfig->getBaseURL() . "/api");
		unset($_COOKIE['api-access-token']);

		$iRestResponse->data['result']['auth_token'] = $_COOKIE['api-access-token'] ?? null;
	}

	echo $iRestResponse->build("json");

?>