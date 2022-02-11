import { Injectable } from '@angular/core';

import { environment } from '../../../environments/environment'
import { HttpClientService } from '../../app-common/service/http-client.service';
import { iStaff } from './i-staff';





@Injectable({
  providedIn: 'root'
})

export class StaffService
{
	public readonly _API_BASE_URL : string = environment._API_URL_OFFICE_STAFF;

	constructor( private $_http: HttpClientService )
	{
		
	}





	public create( $payload : iStaff )
	{
		let password = $payload?.auth?.password;
		let rePassword = $payload?.auth?.rePassword;

		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded",
				'Auth-Login-Password' : password || "",
				'Auth-Login-Re-Password' : rePassword || ""
			},
			withCredentials : true
		};


		let payload =
		{
			param :
			{
				auth :
				{
					username : $payload?.auth?.username
				},
				account:
				{
					firstname : $payload?.firstname,
					lastname : $payload?.lastname,
					email : $payload?.email,
					location_address : $payload?.location ?? " "
				},
				task : [ 5, 10, 15 ]
			}
		};

		return this.$_http.post( this._API_BASE_URL + "register.php", payload, httpOptions );
	}





	public search( $payload : iStaff )
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





	public searchCoworkers( $payload : iStaff )
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

		return this.$_http.post( this._API_BASE_URL + "search-coworkers.php", payload, httpOptions );
	}
}
