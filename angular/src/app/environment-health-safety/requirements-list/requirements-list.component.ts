import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { EnvironmentHealthSafetyAdminService } from '../../backoffice/admin/environment-health-safety/environment-health-safety-admin.service';





@Component({
  selector: 'app-requirements-list',
  templateUrl: './requirements-list.component.html',
  styleUrls: ['./requirements-list.component.scss']
})
export class RequirementsListComponent implements OnInit
{
	constructor( private $_router : Router, private $_sanitizer: DomSanitizer, private $_EHSSRV : EnvironmentHealthSafetyAdminService ) { }

	ngOnInit(): void
	{
		this.search();
	}





	public searchResult : any = { data : [] };
	public searchXHR ?: Promise<object>;


	public search()
	{
		let responseHdl = ( response : any ) =>
		{
			if( response?.result?.data )
			{
				this.searchResult.data = this._mapAPIResponse(response.result.data);
			}
		}

		this.$_EHSSRV.search({}).then(responseHdl);
	}





	private _mapAPIResponse( $data : any ) : any
	{
		for( let ehs of $data )
		{
			let icon = ehs?.icon;

			if( icon?.url )
			{
				icon.url = this.$_sanitizer.bypassSecurityTrustResourceUrl(icon?.url);
			}
		}

		return $data;
	}
}
