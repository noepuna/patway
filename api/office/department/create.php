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

	use App\Office\Department\DepartmentMeta,
		App\Office\Department\Department;





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
	$department = $post['param']['office_department'] ?? [];

	$departmentMeta =
    [
        'crud_method' => DepartmentMeta::CRUD_METHOD_CREATE,
        'office_department' =>
        [
			'name' => $department['name'] ?? null,
			'description' => $department['description'] ?? null,
			'admin' => $iOFCAdmin
        ]
    ];

    $iDepartmentMeta = DepartmentMeta::createInstance( $iConfig, $departmentMeta );

    if( $iDepartmentMeta instanceof DepartmentMeta )
    {
    	$iDepartment = Department::createInstance( $iConfig, $iDepartmentMeta );
    }
    else
    {
    	$iRestResponse->addError( $iDepartmentMeta );
    }





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && ( $iDepartment ?? null ) )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iRestResponse->data['result']['office_department_id'] = $iDepartment->create();

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>