import { Component, OnInit } from '@angular/core';
import { SupportTicketService } from '../support-ticket.service';
import { iSupportTicket } from '../i-support-ticket';





@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})

export class CreateComponent implements OnInit
{

	constructor( private $_supportTicketSRV : SupportTicketService ) { }

	ngOnInit(): void
	{

	}




	public payload : iSupportTicket = {};
	public ticketId ?: string;
	public formErrors : any;

	public save()
	{
		let responseHdl = ( response : any ) =>
		{
			this.ticketId = undefined;
			this.formErrors = undefined;

			if( response?.error )
			{
				this.formErrors = response.error;
			}

			if( response?.result?.event_id )
			{
				this.ticketId = response.result.event_id;
			}
		}

		this.$_supportTicketSRV.create(this.payload).then(responseHdl);
	}
}
