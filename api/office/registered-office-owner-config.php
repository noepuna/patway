<?php

	require_once "vendor/autoload.php";

	use App\Auth\Auth;
	use App\Auth\AuthMeta;
	use App\Office\Owner;
	use App\Office\OwnerMeta;





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
			'username' => "joe@callme.com",
			'password' => "potus",
			'previleges' => [4]
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

	$ownerMeta =
	[	'crud_method' => OwnerMeta::CRUD_METHOD_READ,
		'account' =>
		[
			'auth' => $iAuth
		]
	];

	$iOwner = null;
	$iOwnerMeta = OwnerMeta::createInstance($iConfig, $ownerMeta);

	if( $iOwnerMeta instanceof OwnerMeta )
	{
		$iOwner = Owner::createInstance($iConfig, $iOwnerMeta);

		if( !is_a($iOwner, Owner::t_UtilOps_classWithBackSlash()) || !$iOwner->getId() )
		{
			die(header("HTTP/1.0 403 Forbidden Access"));
		}
	}
	else
	{
		die(header("HTTP/1.0 403 Forbidden Access"));
	}

?>