import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { DepartmentService } from '../department.service';





@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.scss']
})
export class SearchComponent implements OnInit
{
	constructor( private $_router : Router, private $_deptSRV : DepartmentService ) { }





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

		this.$_deptSRV.search({}).then(responseHdl);
	}





	public navigateStaffDetails( $id : string )
	{
		this.$_router.navigate(["backoffice/admin/department/update", $id]);
	}
}