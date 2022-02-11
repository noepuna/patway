import { Injectable } from '@angular/core';

import { environment } from '../../environments/environment'
import { HttpClientService } from '../app-common/service/http-client.service';
import { iSupportTicket } from './i-support-ticket';





@Injectable({
  providedIn: 'root'
})

export class SupportTicketService
{
	public readonly _API_BASE_URL : string = environment._API_URL_SUPPORT_TICKET;

	public readonly severity =
	{
		1 : "low",
		2 : "high",
		3 : "medium"
	}

	public readonly status =
	{
		1 : "active",
		2 : "pending",
		3 : "closed"
	}





	constructor( private $_http: HttpClientService )
	{
		
	}

	public create( $payload : iSupportTicket )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : { ticket: $payload } };

		return this.$_http.post( this._API_BASE_URL + "create.php", payload, httpOptions );
	}





	public view( $payload : { id : string } )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : { ticket: $payload } };

		return this.$_http.post( this._API_BASE_URL + "details.php", payload, httpOptions );
	}





	public search( $payload : {} )
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

		};

		return this.$_http.post( this._API_BASE_URL + "search.php", payload, httpOptions );
	}
}
