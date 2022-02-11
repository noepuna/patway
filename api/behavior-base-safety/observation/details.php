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

	use App\BehaviorBaseSafety\Observation,
		App\BehaviorBaseSafety\ObservationMeta;





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
	$observation = $post['param']['bbs_observation'] ?? [];

    $observationMeta =
    [
        'crud_method' => ObservationMeta::CRUD_METHOD_READ,
        'bbs_observation' =>
        [
        	'id' => $observation['id'] ?? 2,
        	'co_worker' => $iAccount
        ]
    ];

    $iObservationMeta = ObservationMeta::createInstance( $iConfig, $observationMeta );

	if( $iObservationMeta instanceof ObservationMeta )
	{
		$iObservation = Observation::createInstance( $iConfig, $iObservationMeta );
	}
	else
	{
		$iRestResponse->addError( $iObservationMeta );
	}





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iObservation )
	{
		$iRestResponse->data['result']['bbs_observation'] =
		[
			'id' => $iObservation->getId(),
			'types' => $iObservation->getTypes(),
			'observer' => [ 'id' => $iObservation->getObserver() ],
			'supervisor' => [ 'id' => $iObservation->getSupervisor() ],
			'notes' => $iObservation->getNotes(),
			'recommendation' => $iObservation->getRecommendation(),
			'action_taken' => $iObservation->getActionTaken(),
			'feedback_to_coworkers' => $iObservation->getFeedbackToCoworkers(),
			'attachment_file' => null,
			'created_by' => $iObservation->getCreatedBy(),
			'date_created' => $iObservation->getDateCreated(),
			'deleted' => $iObservation->isDeleted()
		];

		if( $iAttachment = $iObservation->getAttachmentFile() )
		{
			$iRestResponse->data['result']['bbs_observation']['attachment_file'] = $iAttachment->getUrlPath();
		}

		//this is a temporary code. you can do better
		if( $observer = $iAccount::t_AccountOps_getInfoById($iConfig, $iObservation->getObserver()) )
		{
			$firstname = $observer['firstname'] ?? "";
			$lastname = $observer['lastname'] ?? "";

			$iRestResponse->data['result']['bbs_observation']['observer'] =
			[
				'id' => $iObservation->getObserver(),
				'name' => ($firstname ? $firstname . "\x20" : "\0") . $lastname,
				'email' => $observer['email'] ?? null
			];
		}

		if( $supervisor = $iAccount::t_AccountOps_getInfoById($iConfig, $iObservation->getSupervisor()) )
		{
			$firstname = $supervisor['firstname'] ?? "";
			$lastname = $supervisor['lastname'] ?? "";

			$iRestResponse->data['result']['bbs_observation']['supervisor'] =
			[
				'id' => $iObservation->getSupervisor(),
				'name' => ($firstname ? $firstname . "\x20" : "\0") . $lastname,
				'email' => $supervisor['email'] ?? null
			];
		}

		//end of temporary code

		$mapFn = function( $iProperty )
		{
			return
			[
				'id' => $iProperty->getId(),
				'value' => $iProperty->getValue()
			];
		};

		$iRestResponse->data['result']['bbs_observation']['properties'] = array_map( $mapFn, $iObservation->getProperties() );
	}

	echo $iRestResponse->build("json");

?>