<?php

	require_once "vendor/autoload.php";

	use App\Auth\Auth;
	use App\Auth\AuthMeta;
	use App\Office\Staff\Staff;
	use App\Office\Staff\StaffMeta;





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
	[	'crud_method' => StaffMeta::CRUD_METHOD_READ,
		'account' =>
		[
			'auth' => $iAuth
		]
	];

	$iOFCStaff = null;
	$iOFCStaffMeta = StaffMeta::createInstance($iConfig, $adminMeta);

	if( $iOFCStaffMeta instanceof StaffMeta )
	{
		$iOFCStaff = Staff::createInstance($iConfig, $iOFCStaffMeta);

		if( !is_a($iOFCStaff, Staff::t_UtilOps_classWithBackSlash()) || !$iOFCStaff->getId() )
		{
			die(header("HTTP/1.0 403 Forbidden Access"));
		}
	}
	else
	{
		die(header("HTTP/1.0 403 Forbidden Access"));
	}

?>