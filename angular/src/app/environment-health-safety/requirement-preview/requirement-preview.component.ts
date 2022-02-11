import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { EnvironmentHealthSafetyAdminService } from '../../backoffice/admin/environment-health-safety/environment-health-safety-admin.service';





@Component({
  selector: 'app-requirement-preview',
  templateUrl: './requirement-preview.component.html',
  styleUrls: ['./requirement-preview.component.scss']
})
export class RequirementPreviewComponent implements OnInit
{
	public eventSettings : any;

	constructor( private $_route : ActivatedRoute, private $_router : Router, private $_sanitizer: DomSanitizer, private $_EHSADMINSRV : EnvironmentHealthSafetyAdminService ) { }

	ngOnInit(): void
	{
		let paramsEvt = ( params : any ) =>
		{
			this.fetchDetails(params.ehs_id);
		}

		this.$_route.params.subscribe(paramsEvt);
	}





	public viewXHR ?: Promise<object>;
	public iEHS : any;

	public fetchDetails( $EHSid : string )
	{
		let responseHdl = ( response : any ) =>
		{
			if( response?.error )
			{
				this.$_router.navigate(['404.html'], {skipLocationChange: true});
			}

			if( response?.result?.ehs )
			{
				let ehs = response.result.ehs;

				this.iEHS =
				{
					id : ehs?.id,
					name : ehs?.name,
					description : ehs?.description,
					icon : this.$_sanitizer.bypassSecurityTrustResourceUrl(ehs?.icon),
					createdBy : ehs.created_by,
					enabled : ehs.enabled,
					deleted : ehs?.deleted,

					settings : ehs?.settings
				};

				if( ehs?.attachment )
				{
					ehs.attachment += "#toolbar=0";

					this.iEHS.attachment = this.$_sanitizer.bypassSecurityTrustResourceUrl(ehs.attachment);
				}
			}

			let settings = this.iEHS?.settings;

			this.eventSettings = settings?.length && settings.find( (entry : any) => entry?.name === "has_event" );

			return response;
		}

		this.viewXHR = this.$_EHSADMINSRV.view({ id: $EHSid }).then(responseHdl);
	}
}
