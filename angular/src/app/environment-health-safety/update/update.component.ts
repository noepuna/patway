import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { MessagingService } from '../../messaging/messaging.service';
import { EnvironmentHealthSafetyService } from '../environment-health-safety.service';
import { EnvironmentHealthSafetyAdminService } from '../../backoffice/admin/environment-health-safety/environment-health-safety-admin.service';
import { iEvent } from '../i-event';
import { iSearchRequest } from '../../api-patway/i-search-request';

import { forkJoin, from, of } from 'rxjs';
import { mergeMap } from 'rxjs/operators';





@Component({
  selector: 'app-update',
  templateUrl: './update.component.html',
  styleUrls: ['./update.component.scss']
})
export class UpdateComponent implements OnInit
{
	constructor( private $_route : ActivatedRoute, private $_router : Router, private $_msgSRV : MessagingService, private $_ehsSRV : EnvironmentHealthSafetyService, private $_EHSADMINSRV : EnvironmentHealthSafetyAdminService ) { }





	public riskLevel : any = [];
	public status : any = [];

	ngOnInit(): void
	{
		// get requirement details

		let paramsEvt = ( params : any ) =>
		{
			this._propagateDetails(this.iEvent.id = params.message_id);
		}

		this.$_route.params.subscribe(paramsEvt);

		// get risk level details

		let riskLevels : any = this.$_ehsSRV.riskLevelEnum;

		for( let i in riskLevels )
		{
			this.riskLevel.push({ id : i, name : riskLevels[i] });
		}

		// get status details

		let statusEnum : any = this.$_ehsSRV.statusEnum;

		for( let i in statusEnum )
		{
			this.status.push({ id : i, name : statusEnum[i] });
		}
	}





	public fetchAllXHR ?: Promise<any>;

	private _propagateDetails( $msgId : string ) : Promise<any>
	{
		return this.fetchAllXHR = forkJoin
		(
			this.fetchDetails($msgId),
			this.fetchRequirementDetails($msgId),
			this.fetchRecipients($msgId),
			this.fetchHashtags($msgId)

		).toPromise();
	}





	public iEvent : iEvent = { hashtagEntries : [] };

	public fetchDetails( $msgId : string )
	{
		let viewEvt = ( response : any ) =>
		{
			let event = response?.result?.sent_message;

			if( event )
			{
				this.iEvent.id = event?.id;
				this.iEvent.requirementId = event?.ehs;
				this.iEvent.riskLevel = event?.risk_level;
				this.iEvent.message = event?.message;
				this.iEvent.title = event?.title;
				this.iEvent.location = event?.location;
				this.iEvent.description = event?.description;
				this.iEvent.dateStart = event?.date_start;
				this.iEvent.dateEnd = event?.date_end;
				this.iEvent.status = event?.status;
			}

			return response;
		}

		let payload = { id : $msgId }

		return this.$_ehsSRV.view(payload).then(viewEvt);
	}





	public fetchRequirementDetails( $ehsId : string )
	{
		let viewEvt = ( response : any ) =>
		{
			return response?.result?.ehs;
		}

		return this.$_EHSADMINSRV.view({id: $ehsId}).then(viewEvt);
	}





	public fetchRecipients( $msgId : string )
	{
		let payload : any = { param : { filter : [ { name: "message_id" , value : $msgId, arithmetic: "=", logic: "AND" } ] } };

		let mapEvt : any = ( data : any ) =>
		{
			return {
				id : data?.id,
				firstname : data?.name,
				deleted : false
			};
		}

		let callbackEvt = ( response : any ) =>
		{
			let recipientsFromRes : any[] = response?.result?.data ?? [];

			this.iEvent.recipients = recipientsFromRes.map( mapEvt );
		}

		return this.$_msgSRV.getRecipients(payload).then(callbackEvt);
	}





	public hashTagXHR ?: Promise<object>;
	public iHashTags = [];

	public fetchHashtags( $msgId : string )
	{
		let payload : iSearchRequest =
		{
			param :
			{
				filter:
				[
					{
						name: "id",
						value: $msgId,
						arithmetic_operator: "=",
						logic_operator: "AND"
					}
				]
			}
		};

		let callbackEvt = ( response : any ) =>
		{
			let mapFn = ( entry : any ) =>
			{
				return { name : entry.hashtag, deleted : entry?.deleted ?? 0 };
			}

			this.iEvent.hashtagEntries = ( response?.result?.data ?? [] ).map( mapFn );

			// if desktop is not present, include one

			let isDesktop = this.iEvent.hashtagEntries!.filter( ( entry : any ) => "desktop" === entry?.name );

			if( 0 === isDesktop.length )
			{
				this.iEvent.hashtagEntries!.push({ name : "desktop", deleted : 1 })
			}

			return response;
		}

		return this.$_msgSRV.getHashTags(payload).then(callbackEvt);
	}





	public msgId ?: string;
	public formErrors : any;
	public EHSErr : any;

	public update()
	{
		let updateEvt = ( response : any ) =>
		{
			this.formErrors = undefined;

			if( response?.error )
			{
				this.formErrors = this._mapAPIErr(response.error);
			}

			this.EHSErr = response?.error?.ehs_message;

			if( response?.result?.ehs_message )
			{
				this.msgId = response.result.ehs_message;
			}

			return response;
		}

		let fromUpdateEvt = ( response : any ) =>
		{
			return this.iEvent.id ? this._propagateDetails( this.iEvent.id ) : of(null);
		};

		return from( this.$_ehsSRV.update(this.iEvent).then(updateEvt) ).pipe( mergeMap( fromUpdateEvt ) ).toPromise();
	}





	/*public onFileChange( $event : any )
	{
	    if( $event.target.files.length > 0 )
	    {
	    	this.payload.icon_file.resource = $event.target.files[0];
	    }
	}*/





	private _mapAPIErr( $errors : any )
	{
		let $recipientErr = $errors?.message?.recipients;

		if( typeof $recipientErr === "string" )
		{
			$errors.message.recipients = [ $recipientErr ];
		}

		return $errors;
	}
}
