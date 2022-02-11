<?php

	/**
	 * API Method Description:
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

	use App\Auth\Auth,
		App\Auth\AuthMeta;





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
	$auth = $post['param']['auth'] ?? [];

    $authMeta =
    [
        'crud_method' => AuthMeta::CRUD_METHOD_UPDATE,
        'auth' =>
        [
        	'id' => $iAuth->getId(),
        	'current_password' => $auth['current_password'] ?? null,
        	'password' => $auth['password'] ?? null,
        	're_password' => $auth['re_password'] ?? null
        ]
    ];

    $iAuthMeta = AuthMeta::createInstance( $iConfig, $authMeta );

	if( !is_a($iAuthMeta, AuthMeta::t_utilOps_classWithBackslash()) )
	{
		$iRestResponse->addError( $iAuthMeta );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iAuth )
	{
		$iRestResponse->data['result'] = $iAuth->update($iAuthMeta);
	}

	echo $iRestResponse->build("json");

?>