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
	require_once "api/office/admin/registered-office-admin-config.php";

	use App\Auth\Auth,
		App\Auth\AuthMeta,
		App\Auth\AuthPrevilegeMeta,
		App\Account\Task\Task,
		App\Account\Task\TaskMeta,
		App\Office\Staff\Staff,
		App\Office\Staff\StaffMeta;





	/*
	 * API Authorization
	 *
	 * ...
	 */
	$iConfig 		= new App\Config;
	$iRestResponse 	= new Resource\APIResponse();
	$iHttpHeaders 	= new Core\Http\HttpHeaders($iConfig);





	/*
	 *	API Request Validations.
	 *
	 * ...
	 *
	 */
	$param = @$post['param'];

	$authMeta =
	[
		'crud_method' => AuthMeta::CRUD_METHOD_CREATE,
		'auth' =>
		[
			'username' => @$param['auth']['username'],
			'password' => $iHttpHeaders->getValueOf("Auth-Login-Password"),
			're_password' => $iHttpHeaders->getValueOf("Auth-Login-Re-Password"),
			'previleges' => [ AuthPrevilegeMeta::STAFF ]
		]
	];

	//
	// create authorization
	//
	$iAuth = null;
	$iAuthMeta = AuthMeta::createInstance( $iConfig, $authMeta );

	if( $iAuthMeta instanceof AuthMeta )
	{
		$iAuth = Auth::createInstance( $iConfig, $iAuthMeta );
	}
	else
	{
		$iRestResponse->addError($iAuthMeta);
	}

	//
	// validate office owner information
	//
	$staffMeta =
	[
		'crud_method' => StaffMeta::CRUD_METHOD_CREATE,
		'account' =>
		[
			'auth' => $iAuth,
			'firstname' => @$param['account']['firstname'],
			'middlename' => @$param['account']['middlename'],
			'lastname' => @$param['account']['lastname'],
			'email' => @$param['account']['email'],
			'location_address' => @$param['account']['location_address'],
			'tel_num' => @$param['account']['tel_num'],
			'mobile_num' => @$param['account']['mobile_num']
		],
        'billing' =>
        [

        ],
        'staff' =>
        [
            'admin' => $iOFCAdmin
        ]
	];

	//
	// include basic tasks
	//
	if( is_array($param['task'] ?? null) )
	{
	    foreach( $param['task'] as $taskId )
	    {
		    $leadMetaAllAccess =
		    [
		        'crud_method' => TaskMeta::CRUD_METHOD_READ,
		        'task' =>
		        [
		            'id' => $taskId
		        ]
		    ];

		    $iTaskMeta = TaskMeta::createInstance( $iConfig, $leadMetaAllAccess );

		    if( $iTaskMeta instanceof TaskMeta )
		    {
		    	if( $iTask = Task::createInstance( $iConfig, $iTaskMeta ) )
		    	{
		    		$staffMeta['task'][] = $iTask;
		    		continue;
		    	}
		    }

		    $iRestResponse->addError( "App not ready, Account Task Assignment Failed", "app" );
		    break;
	    }
	}

	$iStaffMeta = StaffMeta::createInstance($iConfig, $staffMeta);

	if( false == is_a($iStaffMeta, StaffMeta::t_UtilOps_classWithBackSlash()) )
	{
		$iRestResponse->addError($iStaffMeta);
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() )
	{
		/*
		 * make the account record
		 */
		$iStaff = Staff::createInstance($iConfig, $iStaffMeta);

		if( $iStaff )
		{
			if( $iStaff->create() )
			{
				$iRestResponse->data['result']['account_id'] = $iStaff->getId();
			}
			else
			{
				$iRestResponse->addError( "Account create failed", "app" );
			}
		}
		else
		{
			$iRestResponse->addError( "App not ready", "app" );
		}
	}

	echo $iRestResponse->build("json");

?>