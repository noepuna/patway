import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { EnvironmentHealthSafetyService } from '../environment-health-safety.service';
import { MessagingService } from '../../messaging/messaging.service';
import { iSearchRequest, iFilter } from '../../api-patway';

import { from } from 'rxjs';
import { switchMap } from 'rxjs/operators';





@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit
{
	public riskLevels : any = [];

	constructor
	(
		private $_router : Router,
		private $_route : ActivatedRoute,
		private $_msgSRV : MessagingService,
		private $_EHSSRV : EnvironmentHealthSafetyService

	) { }





	ngOnInit(): void
	{
		let filterEvt = ( params : any ) =>
		{
			let status = params?.status;

			this.searchInfo.payload.param!.filter = [];

			if( status?.length )
			{
				let statusFilter : iFilter =
				{
					name: "status",
					value: params.status,
					arithmetic_operator: "=",
					logic_operator: "AND"
				}

				this.searchInfo.payload.param!.filter!.push(statusFilter);
			}

			this.search();
		}

		this.$_route.queryParams.subscribe(filterEvt);

		this.riskLevels = this.$_EHSSRV.riskLevelEnum;
	}





	public searchInfo : { result : any, payload : iSearchRequest, xhr ?: Promise<object> } =
	{
		result : { data : [] },
		payload : { param : { filter : [] } }
	}

	public search()
	{
		let responseMapEvt = ( data : any ) =>
		{
			data.hashtagEntries = [];

			return data;
		}

		let responseHdl = ( response : any ) =>
		{
			if( response?.result?.data )
			{
				this.searchInfo.result.data = response.result.data.map(responseMapEvt);
			}

			return this.fetchHashtag
			(
				this.searchInfo.result.data
					.filter( ( entry : any ) => entry?.id )
					.map( ( entry : any ) => entry.id )
			);
		}

		return this.$_EHSSRV.search(this.searchInfo.payload).then(responseHdl);
	}





	public hashtagXHR !: Promise<object>;

	public fetchHashtag( msgIdEntries : Array<string> )
	{
		let payload : iSearchRequest =
		{
			param :
			{
				filter:
				[
					{
						name: "id",
						value: msgIdEntries,
						arithmetic_operator: "=",
						logic_operator: "AND"
					}
				]
			}
		};

		let callbackEvt = ( response : any ) =>
		{
			let hashtagEntries;

			if( hashtagEntries = response?.result?.data )
			{
				for( let entry of hashtagEntries )
				{
					this.searchInfo.result.data
						.filter( ( msgEntry : any ) => msgEntry?.id == entry?.msg_id )
						.forEach( ( msgEntry : any ) => msgEntry.hashtagEntries.push(entry?.hashtag) );
				}
			}

			return response;
		}

		return this.hashtagXHR = this.$_msgSRV.getHashTags(payload).then(callbackEvt);
	}





	public navigateStaffDetails( $id : string )
	{
		this.$_router.navigate(["environment-health-safety/view", $id]);
	}
}
