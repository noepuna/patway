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
	require_once "api/office/registered-office-owner-config.php";

	use App\Auth\Auth,
		App\Auth\AuthMeta,
		App\Auth\AuthPrevilegeMeta,
		App\Account\Task\Task,
		App\Account\Task\TaskMeta,
		App\Office\Admin\Admin,
		App\Office\Admin\AdminMeta;





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
	$param = $post['param'] ?? [];

	$ownerAuthMeta =
	[
		'crud_method' => AuthMeta::CRUD_METHOD_READ,
		'auth' =>
		[
			'login_token' => $authMeta['auth']['login_token'],
			'id' => $param['auth']['id'] ?? null,
			'previleges' => [ 5 ]
		]
	];

	$iOwnerAuthMeta = AuthMeta::createInstance( $iConfig, $ownerAuthMeta );

	if( $iOwnerAuthMeta instanceof AuthMeta )
	{
		$iOwnerAuth = Auth::createInstance( $iConfig, $iOwnerAuthMeta );
	}
	else
	{
		$iRestResponse->addError( $iOwnerAuthMeta );
	}

	//
	// validate
	//
    $adminMeta =
    [
        'crud_method' => AdminMeta::CRUD_METHOD_UPDATE,
        'account' =>
        [
        	'auth' => $iOwnerAuth ?? null
        ]
    ];

	//
	// include basic tasks
	//
	if( is_array($param['task'] ?? null) )
	{
	    foreach( $param['task'] as $key => $task )
	    {
		    $leadMetaAllAccess =
		    [
		        'crud_method' => TaskMeta::CRUD_METHOD_READ,
		        'task' =>
		        [
		            'id' => $task['id'] ?? null,
		            'disabled' => !!( $task['disabled'] ?? null )
		        ]
		    ];

		    $iTaskMeta = TaskMeta::createInstance( $iConfig, $leadMetaAllAccess );

		    if( $iTaskMeta instanceof TaskMeta )
		    {
		    	if( $iTask = Task::createInstance( $iConfig, $iTaskMeta ) )
		    	{
		    		$adminMeta['task'][] = $iTask;
		    	}
		    }
		    else
		    {
		    	$iRestResponse->addError( $iTaskMeta, $key );
		    }
	    }
	}

    $iAdminMeta = AdminMeta::createInstance( $iConfig, AdminMeta::searchAndRemove( $adminMeta, null ) );

	if( $iAdminMeta instanceof AdminMeta )
	{
		$iAdmin = Admin::createInstance( $iConfig, $iAdminMeta );
	}
	else
	{
		$iRestResponse->addError( $iAdminMeta );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && ( $iAdmin ?? null ) )
	{
		$iAdmin->update($adminMeta);

		//
		// temporary code
		//
		// replace this when Account Class has the methods to get assigned tasks
		//

		$iDb = $iConfig->getDbAdapter();

		$iDb->query
		("
			SELECT ACC_TASK.task_fk, TASK.label
			FROM `account_tasks` ACC_TASK
			INNER JOIN `app_task` TASK ON ACC_TASK.task_fk = TASK.uid
			WHERE ACC_TASK.auth_fk = :auth_fk AND ACC_TASK.disabled = 0",

			[ 'auth_fk' => $iAdmin->getId() ]
		);

		$iRestResponse->data['tasks'] = [];

		if( $iDb->query_num_rows() )
		{
			$mapFn = function( $data )
			{
				return
				[
					'id' => $data['task_fk'],
					'name' => $data['label'],
				];
			};

			$iRestResponse->data['tasks'] = array_map( $mapFn , $iDb->fetch() );
		}
	}

	echo $iRestResponse->build("json");

?>