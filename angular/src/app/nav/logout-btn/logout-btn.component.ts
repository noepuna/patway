import { Component, OnInit, HostListener } from '@angular/core';
import { Router } from "@angular/router";
import { AuthService } from '../../standard/auth.service';





@Component({
  selector: 'app-logout-btn',
  templateUrl: './logout-btn.component.html',
  styleUrls: ['./logout-btn.component.scss']
})
export class LogoutBtnComponent implements OnInit
{
	constructor( private _router : Router, private $_authSRV : AuthService ) { }

	ngOnInit(): void
	{

	}





	@HostListener('click') onMouseEnter()
	{
		let logoutEvt = ( response : any ) =>
		{
			let result = response?.result;

			if( "auth_token" in result && !result.auth_token )
			{
				this._router.navigate(['/login'], { queryParams : { referer : "" }});
			}
		}

		this.$_authSRV.logout().then(logoutEvt);
	}
}
