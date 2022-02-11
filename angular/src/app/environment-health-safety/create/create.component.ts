import { Component, OnInit, ViewChild, ElementRef, Renderer2 } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { EnvironmentHealthSafetyService } from '../environment-health-safety.service';
import { EnvironmentHealthSafetyAdminService } from '../../backoffice/admin/environment-health-safety/environment-health-safety-admin.service';
import { iEvent } from '../i-event';





@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})
export class CreateComponent implements OnInit
{
	constructor
	(
		private $_route : ActivatedRoute,
		private $_router : Router,
		private $_ehsSRV : EnvironmentHealthSafetyService,
		private $_renderer : Renderer2,
		private $_EHSADMINSRV : EnvironmentHealthSafetyAdminService ) { }





	public riskLevel : any = [];

	ngOnInit(): void
	{
		//
		// get EHS requirement details
		//
		let paramsEvt = ( params : any ) =>
		{
			this.fetchDetails( this.iEvent.requirementId = params.ehs_id );
		}

		this.$_route.params.subscribe(paramsEvt);

		//
		// get EHS risk level details
		//
		let riskLevels : any = this.$_ehsSRV.riskLevelEnum;

		for( let i in riskLevels )
		{
			this.riskLevel.push({ id : i, name : riskLevels[i] });
		}
	}





	public EHSDetailsXHR ?: Promise<any>;

	public fetchDetails( $ehsId : string )
	{
		let viewEvt = ( response : any ) =>
		{
			return response?.result?.ehs;
		}

		this.EHSDetailsXHR = this.$_EHSADMINSRV.view({id: $ehsId}).then(viewEvt);
	}





	public iEvent : iEvent = {};
	public msgId ?: string;
	public formErrors : any;
	public EHSErr : any;

	public save()
	{
		let responseHdl = ( response : any ) =>
		{
			this.msgId = undefined;
			this.EHSErr = response?.error?.ehs_message;

			if( response?.error )
			{
				this.formErrors = this._mapAPIErr(response.error);
			}

			if( response?.result?.ehs_message )
			{
				this.resetForm();

				this.msgId = response.result.ehs_message;
			}

		}

		this.$_ehsSRV.create(this.iEvent).then(responseHdl);
	}

	private _mapAPIErr( $errors : any )
	{
		let $recipientErr = $errors?.message?.recipients;

		if( typeof $recipientErr === "string" )
		{
			$errors.message.recipients = [ $recipientErr ];
		}

		return $errors;
	}





	public onFileChange( $event : any )
	{
	    if( $event.target.files.length > 0 )
	    {
	    	//this.iEvent.icon_file.resource = $event.target.files[0];
	    }
	}





	public setDesktopTag( $event : any )
	{
		this.iEvent.hashtagEntries = [{ name : "desktop", deleted : $event.target.checked ? 0 : 1 }];
	}





	@ViewChild('desktopTag')  desktopTagEl !: ElementRef;
	@ViewChild('attachmentFile') attachmentFileEl !: ElementRef;

	public resetForm()
	{
		this.attachmentFileEl.nativeElement.value = null;
		this.formErrors = undefined;
		this.iEvent = {};

		this.desktopTagEl.nativeElement.checked = false;
	}
}
