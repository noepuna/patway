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

	use App\Auth\AuthMeta,
		App\Auth\Auth,
		App\Office\Staff\StaffMeta,
		App\Office\Staff\Staff,
		App\Office\Department\DepartmentMeta,
		App\Office\Department\Department,
		App\Office\Staff\DepartmentEntryMeta,
		App\Office\Staff\DepartmentEntry;





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

	# create department entry instance

	$entries = $post['param']['department'] ?? [];

	foreach( $entries as $key => $entry )
	{
	    $departmentMeta =
	    [
	    	'crud_method' => DepartmentMeta::CRUD_METHOD_READ,
	    	'office_department' =>
	    	[
	    		'id' => $entry['id'],
	    		'admin' => $iOFCAdmin
	    	]
	    ];

        $iDepartmentMeta = DepartmentMeta::createInstance( $iConfig, $departmentMeta );

	    if( $iDepartmentMeta instanceof DepartmentMeta )
	    {
	    	if( $iDepartment = Department::createInstance($iConfig, $iDepartmentMeta) )
	    	{
				$entryMeta =
			    [
			        'crud_method' => DepartmentEntryMeta::CRUD_METHOD_CREATE,
			        'department_entry' =>
			        [
						'department' => $iDepartment,
						'deleted' => !!( $entry['deleted'] ?? null ),
			        ]
			    ];

			    $iEntryMeta = DepartmentEntryMeta::createInstance( $iConfig, $entryMeta );

			    if( $iEntryMeta instanceof DepartmentEntryMeta )
			    {
			    	$post['param']['department'][$key] = DepartmentEntry::createInstance( $iConfig, $iEntryMeta );
			    }
			    else
			    {
			    	$iRestResponse->addError( $iEntryMeta['department_entry'], "department", $key );
			    }
	    	}
	    }
	    else
	    {
	    	$iRestResponse->addError( $iDepartmentMeta['office_department'], "department", $key );
	    }
	}

    # login admin as staff

    $staffAuthMeta =
    [
        'crud_method' => AuthMeta::CRUD_METHOD_READ,
        'auth' =>
        [
            'id' => $post['param']['id'] ?? null,
            'login_token' => $authMeta['auth']['login_token']
        ]
    ];

	$iStaffAuthMeta = AuthMeta::createInstance( $iConfig, $staffAuthMeta );

	if( $iStaffAuthMeta instanceof AuthMeta )
	{
	    $staffMeta =
	    [
	        'crud_method' => StaffMeta::CRUD_METHOD_READ,
	        'account' =>
	        [
	            'auth' => Auth::createInstance( $iConfig, $iStaffAuthMeta )
	        ]
	    ];

	    $iStaffMeta = StaffMeta::createInstance( $iConfig, $staffMeta );

		if( $iStaffMeta instanceof StaffMeta )
		{
			$iStaff = Staff::createInstance( $iConfig, $iStaffMeta );
		}
	}
	else
	{
		$iRestResponse->addError( $iStaffAuthMeta['auth'], "staff" );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && ( $iStaff ?? null ) )
	{
		$updateMeta['staff'] = $post['param'];

		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iStaff->update( $updateMeta );

		$iRestResponse->data['result']['data'] = $iStaff->getDepartments();

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>