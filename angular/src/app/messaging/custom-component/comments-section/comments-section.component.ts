import { Component, OnInit, OnChanges, SimpleChanges, Input } from '@angular/core';
import { MessagingService } from '../../messaging.service';
import { NotificationService } from '../../../notification/notification.service';
import { iComment } from './i-comment';





@Component({
  selector: 'app-comments-section',
  templateUrl: './comments-section.component.html',
  styleUrls: ['./comments-section.component.scss']
})
export class CommentsSectionComponent implements OnInit, OnChanges
{
	@Input() conversationId : string = "";
	@Input() canReply : boolean = true;

	constructor( private $_messagingSRV : MessagingService, private $_notifSRV : NotificationService ) { }





	public kComments : iComment[] = [];

	ngOnInit(): void
	{
		// noop
	}





	public reply : any = {};

	ngOnChanges( changes: SimpleChanges )
	{
		let filterBuildEvt = ( $response : any ) =>
		{
			let maxId = 0;

			this.kComments.forEach( ($iComment : iComment) =>
			{
				let id = $iComment?.id && parseInt($iComment.id);

				if( id && ( id > maxId ) )
				{
					maxId = id;
				}
			})

			this._recentRepliesPayload.filter.id =
			{
				name: "id",
				arithmetic_operator : ">",
				logic_operator : "AND",
				value : maxId
			}

			return $response;
		}

		let notificationSubscriptionEvt = ( $response : any ) =>
		{
			this.$_notifSRV.getEntries.subscribe( ( $entries : any ) =>
			{
				for( let entry of $entries )
				{
					if( !this.fetchRecentRepliesXHR && ( 8 === entry?.appComponent ) )
					{
						this._fetchRecentReplies();

						break;
					}
				}
			});

			return $response;
		}

		let conversationId = changes?.conversationId?.currentValue;

		if( this.reply.conversationId = conversationId )
		{
			//
			// add the conversation filter for recent replies search
			//
			this._recentRepliesPayload.filter.conversation =
			{
				name: "conversation",
				arithmetic_operator: "=",
				logic_operator : "AND",
				value : conversationId
			}

			//
			// add the conversation filter for previous replies search
			//
			this.prevRepliesPayload.filter = {};

			this.prevRepliesPayload.filter.conversation =
			{
				name: "conversation",
				arithmetic_operator: "=",
				logic_operator : "AND",
				value : conversationId
			}

			//
			// start fetching all replies
			//
			this._fetchPrevReplies().then(filterBuildEvt).then(notificationSubscriptionEvt);
		}
    }





	public sendReplyXHR : any;
	public sendReplyErr : any;

	public sendReply( $event : any ) : any
	{
		let oldMessage = this.reply.message;

		this.reply.message = null;

		let replyEvt = ( response : any ) =>
		{
			this.sendReplyXHR = null;
			this.sendReplyErr = null;

			if( response?.error )
			{
				this.sendReplyErr = response.error;
			}

			return response;
		};

		//
		// prevent spamming of sending repliest
		//
		if( !this.sendReplyXHR )
		{
			let payload = { conversationId: this.reply.conversationId, message: oldMessage };

			this.sendReplyXHR = this.$_messagingSRV.createReply(payload).then(replyEvt);
		}
	}





	public fetchReplies( $payload : any ) : any
	{
		let fetchEvt = ( $response : any ) =>
		{
			this.fetchPrevRepliesXHR = null;

			return $response;
		}

		return this.fetchPrevRepliesXHR = this.$_messagingSRV.getReplies({ param : $payload }).then(fetchEvt);
	}





	public fetchPrevRepliesXHR : any = null;

	public prevRepliesPayload : any =
	{
		limit 	: 2,
		filter 	: {}
	};

	public _fetchPrevReplies()
	{
		let fetchEvt = ( $response : any ) =>
		{
			this.fetchPrevRepliesXHR = null;

			let comments = $response?.result?.data;

			if( comments?.length )
			{
				for( let comment of comments )
				{
					this.kComments.push(comment);
				}
			}

			this.prevRepliesPayload = { pagetoken: $response?.result?.pagetoken };

			return $response;
		}

		return this.fetchPrevRepliesXHR = this.$_messagingSRV.getReplies({ param : this.prevRepliesPayload }).then(fetchEvt);
	}





	public fetchRecentRepliesXHR : any = null;

	private _recentRepliesPayload : any =
	{
		limit 	: 2,
		filter 	: {}
	};

	private _fetchRecentReplies()
	{
		let recentRepliesEvt = ( $response : any ) => 
		{
			this.fetchRecentRepliesXHR = null;

			let comments = $response?.result?.data;

			if( comments?.length )
			{
				for( let comment of comments )
				{
					this.kComments.unshift(comment);
				}
			}

			return $response;
		}

		let nextPageTokenEvt = ( $response : any ) =>
		{
			this._recentRepliesPayload = { pagetoken: $response?.result?.pagetoken };

			if( $response?.result?.data?.length )
			{
				this._fetchRecentReplies();
			}

			return $response;
		}

		return this.fetchRecentRepliesXHR = this.$_messagingSRV.getRecentReplies({ param : this._recentRepliesPayload }).then(recentRepliesEvt).then(nextPageTokenEvt);
	}
}
