import { Injectable } from '@angular/core';

import { Observable, BehaviorSubject } from 'rxjs';

import { environment } from '../../environments/environment';
import { HttpClientService } from '../app-common/service/http-client.service';

@Injectable()
export class AuthService
{
	public readonly _API_BASE_URL : string = environment._API_URL_AUTH;

	constructor( private _http : HttpClientService )
	{
		//
	}

	private _loginAuthToken = new BehaviorSubject<string | null>(null);

	get getLoginAuthToken()
	{
		return this._loginAuthToken.asObservable();
	}

	public login( $username : string, $password : string, $previleges ?: Array<string | number> ) : Promise<{}>
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded", 'Auth-Login-Password' : $password || "" },
			withCredentials : true
		};

		let payload = { 'param': { 'auth' : { 'username' : $username, 'previleges' : $previleges } } };

		let successFn = ( response : any ) =>
		{
			if( "result" in response )
			{
				this._loginAuthToken.next(response.result.auth_token);
			}

			return response;
		}

		return this._http.post( this._API_BASE_URL + "/login.php", payload, httpOptions ).then(successFn);
	}

	public logout()
	{
		let httpOptions = 
		{
			withCredentials : true
		};

		let logoutEvt = ( response : any ) =>
		{
			let result = response?.result;

			if( result && ("auth_token" in result) && !result.auth_token )
			{
				this._loginAuthToken.next(result.auth_token);
			}

			return response;
		}

		return this._http.post( this._API_BASE_URL + "/logout.php", {}, httpOptions ).then(logoutEvt);
	}

	public getAccessToken()
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = {};

		let successFn = ( response : any ) =>
		{
			if( "result" in response )
			{
				this._loginAuthToken.next(response.result.auth_token);
			}

			return response;
		}

		return this._http.post( this._API_BASE_URL + "/get-access-token.php", payload, httpOptions ).then(successFn);
	}





	public changePassword( $data : any )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : $data };

		return this._http.post( this._API_BASE_URL + "/change-password.php", payload, httpOptions );
	}
}
