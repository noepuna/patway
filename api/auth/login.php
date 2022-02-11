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
		App\Auth\AuthPrevilegeMeta,
		App\Account\Merchant\Merchant,
		App\Account\Merchant\MerchantMeta;





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
	$param = @$post['param'];

	$authMeta =
	[
        'crud_method' => AuthMeta::CRUD_METHOD_READ,
        'auth' =>
        [
			'username' => @$param['auth']['username'],
			'password' => $iHttpHeaders->getValueOf("Auth-Login-Password"),
			'previleges' => @$param['auth']['previleges'] ?? []
        ]
	];

	$iAuthMeta = AuthMeta::createInstance( $iConfig, $authMeta );

	if( $iAuthMeta instanceof AuthMeta )
	{
		$iAuth = Auth::createInstance( $iConfig, $iAuthMeta );
	}
	else
	{
		$iRestResponse->addError($iAuthMeta);
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() )
	{

		/*
		 * create the auth-token
		 */
		if( $iAuth = Auth::createInstance( $iConfig, $iAuthMeta ) )
		{
			$accessToken = $iAuth->login();

			setcookie("api-access-token", $accessToken, time()+60*60*24*30, $iConfig->getBaseURL() . "/api");
			$iRestResponse->data['result']['auth_token'] = $accessToken;
		}
		else
		{
			$iRestResponse->addError( "App not ready", "app" );
		}
	}

	echo $iRestResponse->build("json");

?>