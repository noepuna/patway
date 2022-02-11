import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { SupportTicketService } from '../support-ticket.service';
import { iSupportTicket } from '../i-support-ticket';





@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.scss']
})

export class ViewComponent implements OnInit
{
	public ticketSeverity : any = this.$_supportTicketSRV.severity;

	constructor( private $_route : ActivatedRoute, private $_router : Router, private $_supportTicketSRV : SupportTicketService ) {}





	ngOnInit(): void
	{
		let paramsEvt = ( params : any ) =>
		{
			this.fetchDetails(params.id);
		}

		this.$_route.params.subscribe(paramsEvt);
	}





	public viewXHR ?: Promise<object>;
	public ticket : any = {};


	public fetchDetails( $ticketId : string )
	{
		let responseHdl = ( response : any ) =>
		{
			if( response?.error )
			{
				this.$_router.navigate(['404.html'], {skipLocationChange: true});
			}

			if( response?.result?.event )
			{
				let t = response.result.event;

				this.ticket =
				{
					subject : "",
					description : t.description,
					severity : t.severity_id,
					status : t.status_id,
					createdBy : t.created_by,
					dateCreated : t.date_created
				};
			}
		}

		this.$_supportTicketSRV.view({ id: $ticketId }).then(responseHdl);
	}
}
