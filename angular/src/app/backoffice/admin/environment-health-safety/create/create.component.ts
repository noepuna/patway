import { Component, OnInit } from '@angular/core';
import { EnvironmentHealthSafetyAdminService } from '../environment-health-safety-admin.service';





@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})
export class CreateComponent implements OnInit
{
	constructor( private $_ehsSRV : EnvironmentHealthSafetyAdminService ) { }

	ngOnInit(): void
	{
		this.resetForm();
	}





	public payload : any = {};
	public EHSid ?: string;
	public formErrors : any;

	public save()
	{
		let responseHdl = ( response : any ) =>
		{
			this.EHSid = undefined;

			this.formErrors = response?.error;

			if( response?.result?.ehs_id )
			{
				this.resetForm();

				this.EHSid = response.result.ehs_id;
			}
		}

		this.$_ehsSRV.create(this.payload).then(responseHdl);
	}





	public onIconChange( $event : any )
	{
	    if( $event.target.files.length > 0 )
	    {
	    	this.payload.icon_file.resource = $event.target.files[0];
	    }
	}





	public onAttachmentChange( $event : any )
	{
	    if( $event.target.files.length > 0 )
	    {
	    	this.payload.attachment_file.resource = $event.target.files[0];
	    }
	}





	public resetForm()
	{
		this.payload = { icon_file : {}, attachment_file: {} };
		this.formErrors = null;
	}
}
