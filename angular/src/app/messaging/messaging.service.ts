import { Injectable } from '@angular/core';

import { environment } from '../../environments/environment'
import { HttpClientService } from '../app-common/service/http-client.service';
import { iSearchRequest } from '../api-patway/i-search-request';
//import { iObservation } from './i-observation';





@Injectable({
 providedIn: 'root'
})

export class MessagingService
{
	public readonly _API_BASE_URL : string = environment._API_URL_MESSAGING;

	constructor( private $_http: HttpClientService )
	{
		
	}





	public createReply( $payload : any )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		let payload = { param : { message: {} } };

		payload.param.message =
		{
			conversation : $payload?.conversationId,
			message : $payload?.message,
			//attachment_file : $payload?.attachment_file
		};

		return this.$_http.post( this._API_BASE_URL + "create-reply.php", payload, httpOptions );
	}





	public getMessages( $payload : iSearchRequest )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "search.php", $payload, httpOptions );
	}





	public getReplies( $payload : iSearchRequest )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "search-replies.php", $payload, httpOptions );
	}





	public getRecentReplies( $payload : iSearchRequest )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "search-recent-replies.php", $payload, httpOptions );
	}





	public getRecipients( $payload : iSearchRequest )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "search-recipients.php", $payload, httpOptions );
	}





	public getHashTags( $payload : iSearchRequest )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "hashtag/search.php", $payload, httpOptions );
	}
}
