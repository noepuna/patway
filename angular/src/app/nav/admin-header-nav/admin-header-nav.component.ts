import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../standard/auth.service';





@Component({
  selector: 'app-admin-header-nav',
  templateUrl: './admin-header-nav.component.html',
  styleUrls: ['./admin-header-nav.component.scss']
})
export class AdminHeaderNavComponent implements OnInit
{
	constructor( private $_authSRV : AuthService ) { }





	public loginStatusObs : any;

	ngOnInit(): void
	{
		this.loginStatusObs = this.$_authSRV.getLoginAuthToken;
	}





	public logout()
	{
		let logoutEvt = ( response : any ) =>
		{
			
		}

		this.$_authSRV.logout().then(logoutEvt);
	}
}
