import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { BehaviorBaseSafetyService } from '../behavior-base-safety.service';





@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})

export class ListComponent implements OnInit
{
	constructor( private $_router : Router, private $_BBS_ObservationSRV : BehaviorBaseSafetyService ) { }

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

		this.$_BBS_ObservationSRV.search({}).then(responseHdl);
	}





	public navigateStaffDetails( $id : string )
	{
		this.$_router.navigate(["behavior-base-safety/view", $id]);
	}
}
