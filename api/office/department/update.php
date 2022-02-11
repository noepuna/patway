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
        'crud_method' => DepartmentMeta::CRUD_METHOD_UPDATE,
        'office_department' =>
        [
        	'id' => $department['id'] ?? null,
        	'name' => $department['name'] ?? null,
			'description' => $department['description'] ?? null,
			'enabled' => is_null( $department['enabled'] ?? null ) ? null : !!$department['enabled']
        ]
    ];

    # department meta validation

	$departmentMeta = DepartmentMeta::searchAndRemove( $departmentMeta, null );

	# secure the construct requirements for department instance

	$departmentMeta['office_department'] += [ 'id' => $department['id'] ?? null, 'admin' => $iOFCAdmin ?? null ];

	$iDepartment = null;
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
		# save the updates
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iDepartment->update( $iDepartmentMeta );

		$dbTransaction && ( $iRestResponse->hasError() ? $iDb->rollback() : $iDb->commit() );

		# return the updated record
		$iRestResponse->data['result']['office_department'] =
		[
			'id' => $iDepartment->getId(),
			'name' => $iDepartment->getName(),
			'description' => $iDepartment->getDescription(),
			'enabled' => $iDepartment->isEnabled() ? 1 : 0
		];
	}

	echo $iRestResponse->build("json");

?>