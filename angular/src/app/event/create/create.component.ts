import { Component, OnInit } from '@angular/core';
import { EventService } from '../event.service';
import { iEvent } from '../i-event';





@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})

export class CreateComponent implements OnInit
{
	constructor( private $_eventSRV : EventService ) { }

	ngOnInit(): void
	{

	}




	public payload : iEvent = {};
	public eventId ?: string;
	public formErrors : any;

	public save()
	{
		let responseHdl = ( response : any ) =>
		{
			this.eventId = undefined;
			this.formErrors = undefined;

			if( response?.error )
			{
				this.formErrors = response.error;
			}

			if( response?.result?.event_id )
			{
				this.eventId = response.result.event_id;
			}
		}

		this.$_eventSRV.create(this.payload).then(responseHdl);
	}
}
