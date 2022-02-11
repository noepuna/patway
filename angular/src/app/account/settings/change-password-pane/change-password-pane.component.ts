import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../../standard/auth.service';

@Component({
  selector: 'app-change-password-pane',
  templateUrl: './change-password-pane.component.html',
  styleUrls: ['./change-password-pane.component.scss']
})
export class ChangePasswordPaneComponent implements OnInit
{
	constructor( private _authSRV : AuthService ) { }





	ngOnInit(): void
	{
		this.resetForm();
	}





	public saveXHR : any;

	public updateStatus : any;

	public currentPassword : any;
	public password : any;
	public rePassword : any;

	public formErrors : any;

	public save()
	{
		this.updateStatus = null;

		let payload =
		{
			auth:
			{
				current_password: this.currentPassword,
				password: this.password,
				re_password: this.rePassword
			}
		}

		let saveEvt = ( $response : any ) =>
		{
			if( "error" in $response )
			{
				this.formErrors = $response.error;
			}

			if( $response?.result )
			{
				this.updateStatus = $response.result;

				this.resetForm();
			}

			return $response;
		}

		this.saveXHR = this._authSRV.changePassword(payload).then(saveEvt);
	}





	public resetForm()
	{
		this.currentPassword = null;
		this.password = null;
		this.rePassword = null;

		this.formErrors = null;
	}
}
