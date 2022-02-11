<?php

	require_once "vendor/autoload.php";

	use App\Auth\Auth;
	use App\Auth\AuthMeta;
	use App\Office\Admin\Admin;
	use App\Office\Admin\AdminMeta;





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
			'previleges' => [5]
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

	$adminMeta =
	[
		'crud_method' => AdminMeta::CRUD_METHOD_READ,
		'account' =>
		[
			'auth' => $iAuth
		]
	];

	$iOFCAdmin = null;
	$iOFCAdminMeta = AdminMeta::createInstance($iConfig, $adminMeta);

	if( $iOFCAdminMeta instanceof AdminMeta )
	{
		$iOFCAdmin = Admin::createInstance($iConfig, $iOFCAdminMeta);

		if( !is_a($iOFCAdmin, Admin::t_UtilOps_classWithBackSlash()) || !$iOFCAdmin->getId() )
		{
			die(header("HTTP/1.0 403 Forbidden Access"));
		}
	}
	else
	{
		die(header("HTTP/1.0 403 Forbidden Access"));
	}

?>