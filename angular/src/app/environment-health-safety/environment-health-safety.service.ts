import { Injectable } from '@angular/core';

import { environment } from '../../environments/environment'
import { HttpClientService } from '../app-common/service/http-client.service';
import { iEvent } from './i-event';
import { iSearchRequest } from '../api-patway';





@Injectable({
  providedIn: 'root'
})
export class EnvironmentHealthSafetyService
{
	public readonly _API_BASE_URL : string = environment._API_URL_EHS_OBSERVATION;

	constructor( private $_http: HttpClientService )
	{
		
	}





	private _messageKeyMap : any =
	{
		id : "id"
	};

	private _requirementsKeyMap : any =
	{
		requirementId : "ehs",
		riskLevel : "risk_level",
		title: "title",
		location: "location",
		description: "description",
		dateStart: "date_start",
		dateEnd: "date_end",
		status: "status"
	};

	public create( $iEvent : iEvent )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		let iEvent : any = $iEvent;
		let payload : any = { param : { message : { recipients : {} }, ehs_message : {}, hashtag_entries : [] } };

		for( var key in this._messageKeyMap )
		{
			let payloadKey = this._messageKeyMap[key];

			key in iEvent && ( payload.param.message[payloadKey] = iEvent[key] );
		}

		for( var key in this._requirementsKeyMap )
		{
			let payloadKey = this._requirementsKeyMap[key];

			key in iEvent && ( payload.param.ehs_message[payloadKey] = iEvent[key] );
		}

		if( $iEvent.recipients )
		{
			for( let recipientKey in $iEvent.recipients )
			{
				let recipient = $iEvent.recipients[recipientKey];

				payload.param.message.recipients[recipientKey] = { id : recipient?.id, deleted : recipient?.deleted ? 1 : 0 };
			}
		}

		if( $iEvent.hashtagEntries )
		{
			for( let hashtagKey in $iEvent.hashtagEntries )
			{
				let hashtag = $iEvent.hashtagEntries[hashtagKey];

				let hashtagEntryPayload : any = { hashtag : { name : hashtag.name } };

				if( hashtag.deleted !== null )
				{
					hashtagEntryPayload.message_hashtag = { deleted : !!(hashtag?.deleted ?? false ) ? 1 : 0 };
				}

				payload.param.hashtag_entries[hashtagKey] = hashtagEntryPayload;
			}
		}

		return this.$_http.upload( this._API_BASE_URL + "create.php", payload, httpOptions );
	}





	public update( $iEvent : iEvent )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		let iEvent : any = $iEvent;
		let payload : any = { param : { message : { recipients : {} }, ehs_message : {}, hashtag_entries : [] } };

		for( var key in this._messageKeyMap )
		{
			let payloadKey = this._messageKeyMap[key];

			key in iEvent && ( payload.param.message[payloadKey] = iEvent[key] );
		}

		for( var key in this._requirementsKeyMap )
		{
			let payloadKey = this._requirementsKeyMap[key];

			key in iEvent && ( payload.param.ehs_message[payloadKey] = iEvent[key] );
		}

		if( $iEvent.recipients )
		{
			for( let recipientKey in $iEvent.recipients )
			{
				let recipient = $iEvent.recipients[recipientKey];

				payload.param.message.recipients[recipientKey] = { id : recipient?.id, deleted : recipient?.deleted ? 1 : 0 };
			}
		}

		if( $iEvent.hashtagEntries )
		{
			for( let hashtagKey in $iEvent.hashtagEntries )
			{
				let hashtag = $iEvent.hashtagEntries[hashtagKey];

				let hashtagEntryPayload : any = { hashtag : { name : hashtag.name } };

				if( hashtag.deleted !== null )
				{
					hashtagEntryPayload.message_hashtag = { deleted : !!(hashtag?.deleted ?? false ) ? 1 : 0 };
				}

				payload.param.hashtag_entries[hashtagKey] = hashtagEntryPayload;
			}
		}

		return this.$_http.upload( this._API_BASE_URL + "update.php", payload, httpOptions );
	}





	public view( $payload : { id : string } )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : { ehs : $payload } };

		return this.$_http.post( this._API_BASE_URL + "details.php", payload, httpOptions );
	}





	public search( $payload : iSearchRequest = {} )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded"
			},
			withCredentials : true
		};

		let payload =
		{
			param : $payload
		};

		return this.$_http.post( this._API_BASE_URL + "search.php", $payload, httpOptions );
	}





	public readonly statusEnum =
	{
		1: "void",
		2: "open",
		3: "pending",
		4: "closed"
	};





	public readonly riskLevelEnum =
	{
		1: "low",
		2: "medium",
		3: "high",
		4: "critical"
	};
}
