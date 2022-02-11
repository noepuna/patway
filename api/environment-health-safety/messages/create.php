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
		App\Messaging\SendTypeMeta,
		App\Construction\EnvironmentHealthSafety\Messaging\SentMessage,
		App\Construction\EnvironmentHealthSafety\Messaging\SentMessageMeta;

	use	App\Structure\ComponentMeta as AppComponentMeta,
		App\Structure\Component as AppComponent;

	use App\Messaging\Notification\NotificationMeta,
		App\Messaging\Notification\Notification;

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
		'crud_method' => SentMessageMeta::CRUD_METHOD_CREATE,
		'message' =>
		[
			'conversation' => $message['conversation'] ?? null,
			'type' => \App\Messaging\SendTypeMeta::DIRECT,
			'group' => $message['group'] ?? null,
			'recipients' => $message['recipients'] ?? [],
			'sender' => $iAccount
		],
		'ehs_message' =>
		[
			'ehs' => $ehsMessage['ehs'] ?? null,
			'status' => SentMessageMeta::STATUS_OPEN,
			'title' => $ehsMessage['title'] ?? null,
			'description' => $ehsMessage['description'] ?? null,
			'location' => $ehsMessage['location'] ?? null,
			'risk_level' => $ehsMessage['risk_level'] ?? null,
			'date_start' => $ehsMessage['date_start'] ?? null,
			'date_end' => $ehsMessage['date_end'] ?? null
		]
	];

    # recipients segment validation

	$recipientMap = function( $data )
	{
		$iRecipient = new Recipient;

		$iRecipient->id = $data['id'] ?? null;

		return $iRecipient;
	};

	if( is_array($meta['message']['recipients']) )
	{
		$meta['message']['recipients'] = array_map($recipientMap, $meta['message']['recipients']);
	}

    # ehs message general validation

    $iSentMessageMeta = SentMessageMeta::createInstance( $iConfig, $meta );

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

		$msgId = $iRestResponse->data['result']['ehs_message'] = $iSentMessage->create();

		if( $msgId && ( $iHashtagEntriesMeta ?? null ) )
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





	/*
	 * API Notification process
	 *
	 *
	 */
	/*if( false !== $iRestResponse->hasError() && ( $iSentMessage ?? null ) )
	{
		$meta =
		[
			'crud_method' => NotificationMeta::CRUD_METHOD_CREATE,
			'notification' =>
			[
				'message_id' => $iSentMessage->getId(),
				'title'	=> "Environment Health & Safety Event Message",
				'body' 	=> "You recieved an Environment Health & Safety Event Message",
				'data' 	=>
				[
					'url_link' => "www.facebook.com",
				]
			]
		];

	    //
	    // app component segment validation
	    //
		$componentMeta =
		[
			'crud_method' => AppComponentMeta::CRUD_METHOD_READ,
			'app_component' =>
			[
				'id' => 7
			]
		];

		$iAppComponentMeta = AppComponentMeta::createInstance( $iConfig, $componentMeta );

		$notificationAppComponentInitErrorKeys = [ "notification", "app_component", "initialization" ];

		if( $iAppComponentMeta instanceof AppComponentMeta )
		{
			if( $iAppComponent = AppComponent::createInstance( $iConfig, $iAppComponentMeta ) )
			{
				$meta['notification']['app_component'] = $iAppComponent;
			}
			else
			{
				$iRestResponse->addError( "component initialization failed", ...$notificationAppComponentInitErrorKeys );
			}
		}
		else
		{
			$iRestResponse->addError( "component feature is not available",  ...$notificationAppComponentInitErrorKeys );
		}

		$iMeta = NotificationMeta::createInstance( $iConfig, $meta );

		if( $iMeta instanceof NotificationMeta )
		{
			$notificationInitErrorKeys = [ "notification", "initialization" ];

			if( $iNotification = Notification::createInstance( $iConfig, $iMeta ) )
			{
				if( !$iNotification->create() )
				{
					$iRestResponse->addError( "notification create failed", ...$notificationInitErrorKeys );
				}
			}
			else
			{
				$iRestResponse->addError( "notification initialization failed", ...$notificationInitErrorKeys );
			}
		}
		else
		{
			$iRestResponse->addError( $iMeta );
		}
	}*/

	echo $iRestResponse->build("json");

?>