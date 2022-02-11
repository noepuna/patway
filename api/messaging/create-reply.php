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
		App\Messaging\Reply\Reply,
		App\Messaging\Reply\ReplyMeta;

	use	App\Structure\ComponentMeta as AppComponentMeta,
		App\Structure\Component as AppComponent;

	use App\Messaging\Notification\NotificationMeta,
		App\Messaging\Notification\Notification;





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
	$message = $post['param']['message'] ?? [];

	$meta =
	[
		'crud_method' => ReplyMeta::CRUD_METHOD_CREATE,
		'message' =>
		[
			'conversation' => $message['conversation'] ?? null,
			'type' => \App\Messaging\SendTypeMeta::DIRECT,
			'group' => $message['group'] ?? null,
			'message' => $message['message'] ?? null,
			'sender' => $iAccount
		]
	];

    //
    // ehs message validation
    //
    $iReplyMeta = ReplyMeta::createInstance( $iConfig, $meta );

    if( $iReplyMeta instanceof ReplyMeta )
    {
    	$iReply = Reply::createInstance( $iConfig, $iReplyMeta );
    }
    else
    {
    	$iRestResponse->addError( $iReplyMeta );
    }





	/*
	 * API Request process
	 *
	 * ...
	 */
	if( false === $iRestResponse->hasError() && $iReply ?? null )
	{
		$iDb = $iConfig->getDbAdapter();
		$dbTransaction = $iDb->beginTransaction();

		$iRestResponse->data['result']['ehs_message'] = $iReply->create();

		$dbTransaction && $iDb->commit();
	}





	/*
	 * API Notification process
	 *
	 *
	 */
	if( false === $iRestResponse->hasError() && ( $iReply ?? null ) )
	{
		$body = $iReply->getMessage();

		if( strlen($body) > 39 )
		{
			$body = substr($body, 0, 36) .  "...";
		}

		$meta =
		[
			'crud_method' => NotificationMeta::CRUD_METHOD_CREATE,
			'notification' =>
			[
				'message_id' => $iReply->getConversation(),
				'title'	=> "New Comment",
				'body' 	=> $body,
				'data' 	=>
				[
					'id' => $iReply->getId(),
					'conversation_id' => $iReply->getConversation()
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
				'id' => 8
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
	}

	echo $iRestResponse->build("json");

?>