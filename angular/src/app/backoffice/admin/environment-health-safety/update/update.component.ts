import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { EnvironmentHealthSafetyAdminService } from '../environment-health-safety-admin.service';





@Component({
  selector: 'app-update',
  templateUrl: './update.component.html',
  styleUrls: ['./update.component.scss']
})
export class UpdateComponent implements OnInit
{
	private _ehsId : any;

	constructor( private $_route : ActivatedRoute, private $_router : Router, private $_ehsSRV : EnvironmentHealthSafetyAdminService ) { }





	public initXHR : any;
	public ehs : any = { icon_file : {}, attachment_file: {} };
	public formErrors : any;

	private _responseEvt = ( response : any ) =>
	{
		let ehs;

		if( ehs = response?.result?.ehs )
		{
			this.ehs =
			{
				name: ehs?.name,
				description: ehs?.description,
				attachment: ehs?.attachment,
				icon : ehs?.icon,
				dateCreated : ehs?.date_created,
				enabled : ehs?.enabled,
				deleted : ehs?.deleted
			}
		}

		let searchSettingsEvt = function( entry : any, index : number )
		{
			let settings = response?.result?.ehs?.settings ?? [];

			for( let responseData of settings )
			{
				let findResult;

				if( entry.apiName === responseData?.name )
				{
					entry.isChecked = !!parseInt(responseData?.enabled);

					return;
				}
			}

			entry.isChecked = false;
		}

		this.settings.forEach( searchSettingsEvt );
		this.formErrors = response?.error;

		return response;
	}





	ngOnInit(): void
	{
		let EHSNotFoundEvt = ( response : any ) =>
		{
			response?.error?.ehs?.id && this.$_router.navigate(["404.html"], {skipLocationChange: true});

			return response;
		}

		let searchParamEvt = ( params : any ) =>
		{
			this._ehsId = params?.id;

			let payload = { ehs : { id : this._ehsId } };

			this.initXHR = this.$_ehsSRV.update(payload).then(this._responseEvt).then(EHSNotFoundEvt);
		}

		this.$_route.params.subscribe(searchParamEvt);
	}





	public settings : any[] =
	[
		{ name: "Event Enabled", apiName: "has_event", isChecked : false }
	];





	public updateFlag : any;

	public update()
	{
		this.formErrors = null;

		let saveEvt = ( response : any ) =>
		{
			!response?.errors && (this.updateFlag = true);

			return response;
		}

		let payload : any =
		{
			ehs :
			{
				id: this._ehsId,
				name : this.ehs.name,
				description: this.ehs.description
			},
			settings : {}
		}

		for( let index in this.settings )
		{
			let entry = this.settings[index];

			payload.settings[entry.apiName] = entry.isChecked ? 1 : 0;
		}

		this.initXHR = this.$_ehsSRV.update(payload).then(this._responseEvt).then(saveEvt);
	}





	public onIconChange( $event : any )
	{

	}





	public onAttachmentChange( $event : any )
	{

	}
}
