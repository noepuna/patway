import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { EnvironmentHealthSafetyAdminService } from '../environment-health-safety-admin.service';





@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.scss']
})
export class ViewComponent implements OnInit
{
	constructor( private $_route : ActivatedRoute, private $_router : Router, private $_sanitizer: DomSanitizer, private $_ehsSRV : EnvironmentHealthSafetyAdminService ) {}





	ngOnInit(): void
	{
		let paramsEvt = ( params : any ) =>
		{
			this.fetchDetails(params.id);
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
					attachment : this.$_sanitizer.bypassSecurityTrustResourceUrl(ehs?.attachment),
					createdBy : ehs.created_by,
					enabled : ehs.enabled,
					deleted : ehs?.deleted
				};
			}
		}

		this.$_ehsSRV.view({ id: $EHSid }).then(responseHdl);
	}
}
