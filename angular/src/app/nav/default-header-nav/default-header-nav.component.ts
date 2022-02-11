import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../standard/auth.service';
import { AccountService } from '../../account';
import { iAccount } from '../../account/i-account';





@Component({
  selector: 'app-default-header-nav',
  templateUrl: './default-header-nav.component.html',
  styleUrls: ['./default-header-nav.component.scss']
})
export class DefaultHeaderNavComponent implements OnInit
{
	constructor( private $_authSRV : AuthService, private $_accountSRV : AccountService ) { }





	public loginStatusObs : any;

	ngOnInit(): void
	{
		this.loginStatusObs = this.$_authSRV.getLoginAuthToken;

		let authEvt = ( token : any ) =>
		{
			if( token )
			{
				this._getaccountDetails();
			}
			else
			{
				this.account = {};
			}
		}

		this.$_authSRV.getLoginAuthToken.subscribe( token => token && this._getaccountDetails() );
	}





	public account : iAccount = {};

	private _getaccountDetails()
	{
		let detailsEvt = ( response : any ) =>
		{
			let details;

			if( details = response?.result?.details )
			{
				this.account =
				{
					firstname : details.firstname,
					lastname : details.lastname
				}

				let profilePhoto;

				if( profilePhoto = details?.profile_photo )
				{
					this.account.profilePhoto =
					{
						id : profilePhoto?.id,
						url : profilePhoto?.url_path
					}
				}

				for(let previlege of details?.previleges)
				{
					"staff" === previlege && ( this.account.isStaff = true );
					"admin" === previlege && ( this.account.isAdmin = true );
					"system" === previlege && ( this.account.isSuper = true );
				}
			}
		}

		this.$_accountSRV.getDetails().then(detailsEvt);
	}
}
