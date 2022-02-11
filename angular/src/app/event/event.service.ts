import { Injectable } from '@angular/core';

import { environment } from '../../environments/environment'
import { HttpClientService } from '../app-common/service/http-client.service';
import { iEvent } from './i-event';





@Injectable({
  providedIn: 'root'
})

export class EventService
{
	public readonly _API_BASE_URL : string = environment._API_URL_EVENT;

	constructor( private $_http: HttpClientService )
	{
		
	}





	public create( $payload : iEvent )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : { event: $payload } };

		return this.$_http.post( this._API_BASE_URL + "create.php", payload, httpOptions );
	}





	public view( $payload : { id : string } )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : { event: $payload } };

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
