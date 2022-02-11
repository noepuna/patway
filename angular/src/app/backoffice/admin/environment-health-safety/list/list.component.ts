import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { EnvironmentHealthSafetyAdminService } from '../environment-health-safety-admin.service';





@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit
{
	constructor( private $_router : Router, private $_EHSSRV : EnvironmentHealthSafetyAdminService ) { }

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
				this.searchResult.data = response.result.data;
			}
		}

		this.$_EHSSRV.search({}).then(responseHdl);
	}





	public navigateStaffDetails( $id : string )
	{
		this.$_router.navigate(["/backoffice/admin/environment-health-safety/view", $id]);
	}
}
