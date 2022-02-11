import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from "@angular/router";
import { AuthService } from '../../standard/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})

export class LoginComponent implements OnInit
{
	constructor( private _router : Router, private $_route : ActivatedRoute, private _authSRV : AuthService ){}

	private _homePage : string = "/home"; 	// default homepage
	private _previleges : [ ...any ] = [ 6 ]; 	// default previlege

	ngOnInit(): void
	{
		let paramsEvt = ( params : any ) =>
		{
			if( "previleges" in params )
			{
				this._previleges = params.previleges;
			}

			if( "homepage" in params )
			{
				this._homePage = params.homepage;
			}
		}

		this.$_route.queryParams.subscribe(paramsEvt);
	}

	public loginXHR : any;
	public loginErrors : any;
	public param : any = {};
	public authToken ?: any;

	public login()
	{
		let loginEvt = ( response : any ) =>
		{
			this.loginXHR = null;
			this.loginErrors = [];

			//
			// check if a token is issued
			//
			if( response?.result?.auth_token )
			{
				this._router.navigate([this._homePage]);
			}

			//
			// check if an null auth token is received
			//
			if( "result" in response && "auth_token" in response?.result )
			{
				this.authToken = response.result.auth_token;
			}

			if( response?.error )
			{
				this.loginErrors = response.error;
			}

			return response;
		}

		this.loginXHR = this._authSRV.login(this.param?.username, this.param?.password, this._previleges).then(loginEvt);
	}
}
