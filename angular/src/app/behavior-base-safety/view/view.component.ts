import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { AccountService } from '../../account/account.service';
import { BehaviorBaseSafetyService } from '../behavior-base-safety.service';
import { PropertyService } from '../property/property.service';





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
		private $_bbsObservationSRV : BehaviorBaseSafetyService,
		private $_propertySRV : PropertyService

	) {}





	public BBSTypes : any = [];

	ngOnInit(): void
	{
		let paramsEvt = ( params : any ) =>
		{
			this.fetchDetails(params.id);
		}

		this.$_route.params.subscribe(paramsEvt);
	}





	public viewXHR ?: Promise<object>;
	public iObservation ?: any;

	public fetchDetails( $observationId : string )
	{
		let responseHdl = ( response : any ) =>
		{
			if( response?.error )
			{
				this.$_router.navigate(['404.html'], {skipLocationChange: true});
			}

			if( response?.result?.bbs_observation )
			{
				let o = response.result.bbs_observation;

				let kType = [];

				for(let type of o.types)
				{
					kType.push(type);
				}

				this.iObservation =
				{
					id : o?.id,
					types : kType,
					observer : o?.observer,
					supervisor : o?.supervisor,
					notes : o?.notes,
					recommendation : o?.recommendation,
					actionTaken : o?.action_taken,
					feedbackToCoworkers : o?.feedback_to_coworkers,
					properties: o?.properties,
					createdBy : o?.created_by,
					dateCreated : o?.date_created,
					desc : o?.decription,
					deleted : o?.deleted
				};

				if( o?.attachment_file )
				{
					o.attachment_file += "#toolbar=0";

					this.iObservation.attachmentFile = this.$_sanitizer.bypassSecurityTrustResourceUrl(o?.attachment_file);
				}

				this._constructProperties(o?.properties);
			}
		}

		this.$_bbsObservationSRV.view({ id: $observationId }).then(responseHdl);
	}





	public propTypes : any = [];
	public propCategory : any = [];

	private _constructProperties( $properties : any )
	{
		let appendValueEvt = ( response : any ) =>
		{
			//
			// append values
			//
			let appendValueEvt = ( $data : any ) =>
			{
				let filterEvt = ( $property : any ) =>
				{
					if( parseInt($data.id) === $property.id )
					{
						$property.value = $data.value;
					}
				}

				response
					.map( ( category : any ) => category?.properties ?? [] )
					.forEach( ( properties : any ) => properties.forEach( filterEvt ) );
			}

			$properties.forEach(appendValueEvt);

			//
			// segregte properties into category and types
			//
			this.propCategory = response.filter( ( category : any ) => 5 !== category.id );

			this.propTypes = response
								.filter( ( category : any ) => 5 === category.id )[0].properties
								.filter( ( property : any ) => !!property?.value );

			return response;
		}

		this.$_propertySRV.searchAssortByCat().then( appendValueEvt );
	}
}
