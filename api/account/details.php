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
	require_once "api/account/registered-account-config.php";

	use App\Auth\AuthPrevilegeMeta,
		App\Account\Account,
		App\Account\AccountMeta;





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
	$account = $post['param']['account'] ?? [];

    $accountMeta =
    [
        'crud_method' => AccountMeta::CRUD_METHOD_READ,
        'account' =>
        [
        	'auth' => $iAuth
        ]
    ];

    $iAccountMeta = AccountMeta::createInstance( $iConfig, $accountMeta );

	if( $iAccountMeta instanceof AccountMeta )
	{
		$iAccount = Account::createInstance( $iConfig, $iAccountMeta );
	}
	else
	{
		$iRestResponse->addError( $iAccountMeta );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iAccount )
	{
		$iRestResponse->data['result']['details'] =
		[
			'id' => $iAccount->getId(),
			'firstname' => $iAccount->getFirstName(),
			'middlename' => $iAccount->getMiddleName(),
			'lastname' => $iAccount->getLastName(),
			'previleges' => $iAuth->getPrevileges()
		];

		$previlegesMap = function( $id )
		{
			switch( $id )
			{
				case AuthPrevilegeMeta::STAFF:
					$id = "staff";
				break;

				case AuthPrevilegeMeta::ADMIN:
					$id = "admin";
				break;

				case AuthPrevilegeMeta::SYSTEM:
					$id = "system";
				break;

				default:
					$id = null;
			}

			return $id;
		};

		$iRestResponse->data['result']['details']['previleges'] = array_map($previlegesMap, $iRestResponse->data['result']['details']['previleges']);

		# temporary code for profile photo
		$iDb = $iConfig->getDbAdapter();

		$iDb->query
		("
			SELECT F.uid, F.url_path
			FROM `app_files` F
			INNER JOIN (
			    SELECT `created_by`, MAX(`date_created`) AS date_created
			    FROM `app_files`
			    WHERE `created_by` = :account_id AND `app_component_fk` = 500
			    GROUP BY `created_by`
			) F_MAX ON F.created_by = F_MAX.created_by AND F.date_created = F_MAX.date_created",

			[ 'account_id' => $iAccount->getId() ]
		);

		if( $iDb->query_num_rows() )
		{
			$iRestResponse->data['result']['details']['profile_photo'] = $iDb->fetch()[0];
		}
	}

	echo $iRestResponse->build("json");

?>