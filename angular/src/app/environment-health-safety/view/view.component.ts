import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { AccountService } from '../../account/account.service';
import { MessagingService } from '../../messaging/messaging.service';
import { EnvironmentHealthSafetyService } from '../environment-health-safety.service'
import { iSearchRequest } from '../../api-patway/i-search-request';





@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.scss']
})
export class ViewComponent implements OnInit
{
	constructor
	(
		private $_route : ActivatedRoute,
		private $_router : Router,
		private $_sanitizer: DomSanitizer,
		private $_accountSRV : AccountService,
		private $_msgSRV : MessagingService,
		private $_ehsSRV : EnvironmentHealthSafetyService

	) {}





	ngOnInit(): void
	{
		let paramsEvt = ( params : any ) =>
		{
			this.fetchDetails(params.id);
			this.fetchRecipients(params.id);
			this.fetchHashtags(params.id);
		}

		this.$_route.params.subscribe(paramsEvt);
	}





	public viewXHR ?: Promise<object>;
	public iMsg : any;

	public fetchDetails( $EHSMsgId : string )
	{
		let statusEnum :  any = this.$_ehsSRV.statusEnum;
		let riskLevelTypesEnum : any = this.$_ehsSRV.riskLevelEnum;

		let callbackEvt = ( response : any ) =>
		{
			if( response?.error )
			{
				this.$_router.navigate(['404.html'], {skipLocationChange: true});
			}

			if( response?.result?.sent_message )
			{
				let data = response.result.sent_message;
				let sender = data?.sender;

				this.iMsg =
				{
					status : statusEnum[data?.status],
					id: data?.id,
					type: data?.type,
					title: data?.title,
					description: data?.description,
					riskLevel: riskLevelTypesEnum[data?.risk_level],
					location: data?.location,
					dateStart: data?.date_start,
					dateEnd: data?.date_end,
					ehs: data?.ehs,
					sender:
					{
						id: sender?.id,
						name: sender?.name,
						email: sender?.email
					},
					dateCreated: data?.date_created,
					deleted: data?.deleted
				};
			}
		}

		this.$_ehsSRV.view({ id: $EHSMsgId }).then(callbackEvt);
	}





	public recipientXHR ?: Promise<object>;
	public iRecipients = [];

	public fetchRecipients( $msgId : string )
	{
		let payload : any = { param : { filter : [ { name: "message_id" , value : $msgId, arithmetic: "=", logic: "AND" } ] } };

		let callbackEvt = ( response : any ) =>
		{
			this.iRecipients = response?.result?.data ?? [];
		}

		this.$_msgSRV.getRecipients(payload).then(callbackEvt);
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
			this.iHashTags = response?.result?.data ?? [];

			return response;
		}

		this.$_msgSRV.getHashTags(payload).then(callbackEvt);
	}
}
