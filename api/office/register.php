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
	require_once "api/system/registered-system-config.php";

	use App\Auth\Auth,
		App\Auth\AuthMeta,
		App\Auth\AuthPrevilegeMeta,
		App\Account\Task\Task,
		App\Account\Task\TaskMeta,
		App\Office\Owner,
		App\Office\OwnerMeta,
		App\Office\Admin\Admin,
		App\Office\Admin\AdminMeta,
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
			'previleges' => [ AuthPrevilegeMeta::OFFICE_OWNER, AuthPrevilegeMeta::ADMIN, AuthPrevilegeMeta::STAFF ]
		]
	];

	# create authorization

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

	# validate office owner information

	$ownerMeta =
	[
		'crud_method' => OwnerMeta::CRUD_METHOD_CREATE,
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
		'office' =>
		[
			'name' => @$param['office']['name']
		]
	];

	# validate tasks

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
		    		$ownerMeta['task'][] = $iTask;
		    		continue;
		    	}
		    }

		    $iRestResponse->addError( "App not ready, Account Task Assignment Failed", "app" );
		    break;
	    }
	}

    # validate office owner information

	$iOwnerMeta = OwnerMeta::createInstance($iConfig, $ownerMeta);

	if( false == is_a($iOwnerMeta, OwnerMeta::t_utilOps_classWithBackSlash()) )
	{
		$iRestResponse->addError($iOwnerMeta);
	}

	# validate office admin information

	$adminMeta =
	[
		'crud_method' => AdminMeta::CRUD_METHOD_CREATE,
		'account' => $ownerMeta['account'],
		'billing' => $ownerMeta['billing']
	];

	$iAdminMeta = AdminMeta::createInstance( $iConfig, $adminMeta );

	if( false == is_a($iAdminMeta, AdminMeta::t_utilOps_classWithBackSlash()) )
	{
		$iRestResponse->addError($iAdminMeta);
	}

	# validate office staff information

	$staffMeta =
	[
		'crud_method' => StaffMeta::CRUD_METHOD_CREATE,
		'account' => $ownerMeta['account'],
		'billing' => $ownerMeta['billing']
	];

	$iStaffMeta = StaffMeta::createInstance( $iConfig, $staffMeta );

	if( false == is_a($iStaffMeta, StaffMeta::t_utilOps_classWithBackSlash()) )
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
		$iDb = $iConfig->getDbAdapter();

		$iDb->beginTransaction();

		if( $iOwner = Owner::createInstance($iConfig, $iOwnerMeta) )
		{
			if( !$iOwner->create() )
			{
				$iRestResponse->addError( "Office Owner create failed", "app" );
			}
		}
		else
		{
			$iRestResponse->addError( "App not ready", "app" );
		}

		if( $iOwner && !$iRestResponse->hasError() )
		{
			$iAdminMeta->account = [ 'auth' => $iAuth ];
			$iAdminMeta->admin = [ 'owner' => $iOwner ];

			if( $adminMetaErrors = $iAdminMeta->getErrors() )
			{
				$iRestResponse->addError( $adminMetaErrors );
			}
			else if( $iAdmin = Admin::createInstance( $iConfig, $iAdminMeta ) )
			{
				if( !$iAdmin->create() )
				{
					$iRestResponse->addError( "Office Admin create failed", "app" );
				}
			}
			else
			{
				$iRestResponse->addError( "App not ready", "app" );
			}
		}

		if( ( $iAdmin ?? null ) && !$iRestResponse->hasError() )
		{
			$iStaffMeta->account = [ 'auth' => $iAuth ];
			$iStaffMeta->staff = [ 'admin' => $iAdmin ];

			if( $staffMetaErrors = $iStaffMeta->getErrors() )
			{
				$iRestResponse->addError( $staffMetaErrors );
			}
			else if( $iStaff = Staff::createInstance( $iConfig, $iStaffMeta ) )
			{
				if( $iStaff->create() )
				{
					$iRestResponse->data['result']['account_id'] = $iStaff->getId();
				}
				else
				{
					$iRestResponse->addError( "Office Staff create failed", "app" );
				}
			}
			else
			{
				$iRestResponse->addError( "App not ready", "app" );
			}
		}

		$iRestResponse->hasError() ? $iDb->rollback() : $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>