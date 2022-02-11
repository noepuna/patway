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

	use App\Messaging\Recipient,
		App\Construction\EnvironmentHealthSafety\Messaging\SentMessage,
		App\Construction\EnvironmentHealthSafety\Messaging\SentMessageMeta;

	use App\Messaging\HashTag\HashTag AS MessageHashTag,
		App\Messaging\HashTag\HashTagMeta AS MessageHashTagMeta;





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
	$iPost = $iServer->getPostRequest();
	$message = $post['param']['message'] ?? [];
	$ehsMessage = $post['param']['ehs_message'] ?? [];

	$meta =
	[
		'crud_method' => SentMessageMeta::CRUD_METHOD_UPDATE,
		'message' =>
		[
			'id' => $message['id'] ?? null,
			//'conversation' => $message['conversation'] ?? null,
			//'type' => \App\Messaging\SendTypeMeta::DIRECT,
			'group' => $message['group'] ?? null,
			'recipients' => $message['recipients'] ?? null,
			'sender' => $iAccount
		],
		'ehs_message' =>
		[
			'ehs' => $ehsMessage['ehs'] ?? null,
			'status' => $ehsMessage['status'] ?? null,
			'title' => $ehsMessage['title'] ?? null,
			'description' => $ehsMessage['description'] ?? null,
			'location' => $ehsMessage['location'] ?? null,
			'risk_level' => $ehsMessage['risk_level'] ?? null,
			'date_start' => $ehsMessage['date_start'] ?? null,
			'date_end' => $ehsMessage['date_end'] ?? null
		]
	];

    # recipients validation

	$recipientMap = function( $data )
	{
		$iRecipient = new Recipient;

		$iRecipient->id = $data['id'] ?? null;
		$iRecipient->deleted = $data['deleted'] ? !!$data['deleted'] : null;

		return $iRecipient;
	};

	if( is_array($meta['message']['recipients']) )
	{
		$meta['message']['recipients'] = array_map($recipientMap, $meta['message']['recipients']);
	}

    # ehs message validation

    $iSentMessageMeta = SentMessageMeta::createInstance( $iConfig, $meta, SentMessageMeta::REMOVE_NULL );

    if( $iSentMessageMeta instanceof SentMessageMeta )
    {
    	$iSentMessage = SentMessage::createInstance( $iConfig, $iSentMessageMeta );
    }
    else
    {
    	$iRestResponse->addError( $iSentMessageMeta );
    }

    # hashtag validation

    if( is_array($hashtags = $post['param']['hashtag_entries'] ?? null) )
    {
	    $hashTagMapFn = function( $entry, $key ) use ( $iConfig, $iAccount, $iRestResponse )
	    {
			$hashTagMeta =
			[
				'crud_method' => MessageHashTagMeta::CRUD_METHOD_CREATE,
				'hashtag' =>
				[
					'name' => $entry['hashtag']['name'] ?? null,
					'created_by' => $iAccount
				],
				'message_hashtag' =>
				[
					'deleted' => !!( $entry['message_hashtag']['deleted'] ?? 0 )
				]
			];

			$iHashtagMeta = MessageHashTagMeta::createInstance( $iConfig, $hashTagMeta );

			if( $iHashtagMeta instanceof MessageHashTagMeta )
			{
				return $iHashtagMeta;
			}
			else
			{
				$iRestResponse->addError( $iHashtagMeta, $key );

				return false;
			}
	    };

    	$iHashtagEntriesMeta = array_map($hashTagMapFn, $hashtags, array_keys($hashtags));
    }





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iSentMessage ?? null )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$result = $iRestResponse->data['result']['ehs_message'] = $iSentMessage->update($iSentMessageMeta);

		if( $result && ( $iHashtagEntriesMeta ?? null ) )
		{
			foreach( $iHashtagEntriesMeta as $key => $iHashtagEntryMeta )
			{
				$iHashtagEntryMeta->message_hashtag = [ 'sent_message' => $iSentMessage ];

				if( $msgError = $iHashtagEntryMeta->getLastError() )
				{
					$iRestResponse->addError( $msgError, "message_hashtag", "sent_message" );
				}
				else
				{
					if( $iMessageHashtag = MessageHashTag::createInstance($iConfig, $iHashtagEntryMeta) )
					{
						$iMessageHashtag->create();
					}
				}
			}
		}

		$dbTransaction && $iDb->commit();
	}

	echo $iRestResponse->build("json");

?>