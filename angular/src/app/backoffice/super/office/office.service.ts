import { Injectable } from '@angular/core';

import { environment } from '../../../../environments/environment'
import { HttpClientService } from '../../../app-common/service/http-client.service';
import { iOffice } from './i-office';





@Injectable({
  providedIn: 'root'
})
export class OfficeService
{
	public readonly _API_BASE_URL : string = environment._API_URL_OFFICE;

	constructor( private $_http: HttpClientService )
	{
		
	}

	public create( $payload : iOffice )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded",
				'Auth-Login-Password' : $payload?.password || "",
				'Auth-Login-Re-Password' : $payload?.rePassword || ""
			},
			withCredentials : true
		};

		let payload = { param : {} };

		payload.param =
		{
			auth :
			{
				username : $payload.email
			},
			account :
			{
				firstname : $payload?.firstname,
				lastname : $payload?.lastname,
				email : $payload?.email,
				location_address : $payload?.address,
				tel_num : $payload?.contactNum
			},
			office :
			{
				name : $payload?.email
			},
			task : [ 5, 10, 15 ]
		}

		return this.$_http.post( this._API_BASE_URL + "register.php", payload, httpOptions );
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
