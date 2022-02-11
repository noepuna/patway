import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { SupportTicketService } from '../support-ticket.service';





@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit
{
	public ticketSeverity : any = {};
	public ticketStatus : any = {};

	constructor( private $_router : Router, private $_ticketSRV : SupportTicketService ) { }

	ngOnInit(): void
	{
		this.ticketSeverity = this.$_ticketSRV.severity;
		this.ticketStatus = this.$_ticketSRV.status;

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

		this.$_ticketSRV.search({}).then(responseHdl);
	}





	public navigateTicketDetails( $id : string )
	{
		this.$_router.navigate(["support-ticket/view", $id]);
	}
}
