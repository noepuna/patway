import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { EventService } from '../event.service';





@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit
{
	constructor( private $_router : Router, private $_eventSRV : EventService ) { }

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

		this.$_eventSRV.search({}).then(responseHdl);
	}





	public navigateEventDetails( $id : string )
	{
		this.$_router.navigate(["event/view", $id]);
	}
}
