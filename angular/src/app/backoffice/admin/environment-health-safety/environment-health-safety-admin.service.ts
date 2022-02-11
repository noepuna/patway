import { Injectable } from '@angular/core';

import { environment } from '../../../../environments/environment'
import { HttpClientService } from '../../../app-common/service/http-client.service';





@Injectable({
  providedIn: 'root'
})
export class EnvironmentHealthSafetyAdminService
{
	public readonly _API_BASE_URL : string = environment._API_URL_EHS_OBSERVATION_ADMIN;

	constructor( private $_http: HttpClientService )
	{
		
	}





	public create( $payload : any )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		let payload = { param : { ehs: $payload } };

		return this.$_http.upload( this._API_BASE_URL + "create.php", payload, httpOptions );
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





	public update( $payload : any )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : $payload };

		return this.$_http.post( this._API_BASE_URL + "update.php", payload, httpOptions );
	}





	public search( $payload : any )
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