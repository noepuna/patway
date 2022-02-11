import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { EventService } from '../event.service';
import { iEvent } from '../i-event';





@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.scss']
})

export class ViewComponent implements OnInit
{
	constructor( private $_route : ActivatedRoute, private $_router : Router, private $_eventSRV : EventService ) {}

	ngOnInit(): void
	{
		let paramsEvt = ( params : any ) =>
		{
			this.fetchDetails(params.id);
		}

		this.$_route.params.subscribe(paramsEvt);
	}





	public viewXHR ?: Promise<object>;
	public event : any;

	public fetchDetails( $eventId : string )
	{
		let responseHdl = ( response : any ) =>
		{
			if( response?.error )
			{
				this.$_router.navigate(['404.html'], {skipLocationChange: true});
			}

			if( response?.result?.event )
			{
				let e = response.result.event;

				this.event =
				{
					id: e.id,
					title: e.title,
					description: e.description,
					location: e.location,
					closed: e.closed,
					createdBy : e.created_by,
					dateCreated: e.date_created,
					deleted: e.deleted
				};
			}
		}

		this.$_eventSRV.view({ id: $eventId }).then(responseHdl);
	}
}
