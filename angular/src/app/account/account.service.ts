import { Injectable } from '@angular/core';

import { environment } from '../../environments/environment';
import { HttpClientService } from '../app-common/service/http-client.service';

import { iAccount } from './i-account';
import { BehaviorSubject } from 'rxjs';





@Injectable({
  providedIn: 'root'
})

export class AccountService
{
	public readonly _API_BASE_URL : string = environment._API_URL_ACCOUNT;

	constructor( private _http : HttpClientService )
	{
		//
	}





	private _accountDetails : BehaviorSubject<iAccount> = new BehaviorSubject({});

	get getCachedDetails()
	{
		return this._accountDetails.asObservable();
	}





	public getDetails()
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = {};

		let responseEvt = ( response : any ) =>
		{
			let details

			if( details = response?.result?.details )
			{
				let firstname = details?.firstname;
				let lastname = details?.lastname;
				let middlename = details?.middlename;

				let data : iAccount =
				{
					id : details?.id,
					firstname: firstname,
					lastname: lastname,
					middlename: middlename,
					fullname: details?.fullname ?? [ firstname, middlename, lastname ].join(' ')
				};

				this._accountDetails.next(data);
			}

			return response;
		}

		return this._http.post( this._API_BASE_URL + "details.php", payload, httpOptions ).then(responseEvt);
	}





	public saveProfilePhoto( $data : any )
	{
		let httpOptions =
		{
			headers : {},
			withCredentials : true
		};

		let payload =
		{
			param : $data
		};

		return this._http.upload( this._API_BASE_URL + "upload-profile-photo.php", payload, httpOptions );
	}
}
