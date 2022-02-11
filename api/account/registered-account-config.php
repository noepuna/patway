<?php

	require_once "vendor/autoload.php";

	use App\Auth\Auth;
	use App\Auth\AuthMeta;
	use App\Account\Account;
	use App\Account\AccountMeta;





    $iConfig        = new App\Config;
    $iRestResponse	= new Resource\APIResponse;
    $iHttpHeaders   = new Core\Http\HttpHeaders($iConfig);
	$iServer 		= new Resource\Server;
	$post 			= $iServer->post;

	$authMeta =
	[
		'crud_method' => AuthMeta::CRUD_METHOD_READ,
		'auth' =>
		[
			'login_token' => $iHttpHeaders->getValueOf("Auth-Login-Token")
		]
	];

	/*$x =
	[
        'crud_method' => AuthMeta::CRUD_METHOD_READ,
        'auth' =>
        [
			'username' => "staff",
			'password' => "staff",
			'previleges' => []
        ]
	];

	$iMeta = AuthMeta::createInstance( $iConfig, $x );	// this
	$iAuth = Auth::createInstance($iConfig, $iMeta);	// is 
	$authMeta['auth']['login_token'] = $iAuth->login();	// temporary*/

	$iAuth = null;
	$iAuthMeta = AuthMeta::createInstance($iConfig, AuthMeta::searchAndRemove($authMeta, null));

	if( $iAuthMeta instanceof AuthMeta )
	{
		$iAuth = Auth::createInstance($iConfig, $iAuthMeta);
	}
	else
	{
		$iRestResponse->addError($iAuthMeta);

		die( $iRestResponse->build("json") );
	}

	$accountMeta =
	[
		'crud_method' => AccountMeta::CRUD_METHOD_READ,
		'account' =>
		[
			'auth' => $iAuth
		]
	];

	$iAccount = null;
	$iAccountMeta = AccountMeta::createInstance($iConfig, $accountMeta);

	if( $iAccountMeta instanceof AccountMeta )
	{
		$iAccount = Account::createInstance($iConfig, $iAccountMeta);

		if( !is_a($iAccount, Account::t_UtilOps_classWithBackSlash()) || !$iAccount->getId() )
		{
			die(header("HTTP/1.0 403 Forbidden Access"));
		}
	}
	else
	{
		die(header("HTTP/1.0 403 Forbidden Access"));
	}

?>