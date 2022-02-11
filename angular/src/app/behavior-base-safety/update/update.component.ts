import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { DomSanitizer } from '@angular/platform-browser';
import { BehaviorBaseSafetyService } from '../behavior-base-safety.service';
import { PropertyService } from '../property/property.service';

import { from } from 'rxjs';
import { switchMap } from 'rxjs/operators';





@Component({
  selector: 'app-update',
  templateUrl: './update.component.html',
  styleUrls: ['./update.component.scss']
})
export class UpdateComponent implements OnInit
{
	constructor
	(
		private $_route : ActivatedRoute,
		private $_router : Router, private $_sanitizer: DomSanitizer,
		private $_bbsObservationSRV : BehaviorBaseSafetyService,
		private $_propertySRV : PropertyService

	) {}






	public fetchAllXHR ?: Promise<any>;
	public BBSProperties : any[] = [];

	ngOnInit(): void
	{
		let propEvt = ( response : any ) =>
		{
			this.BBSProperties = response;

			return this.update();
		}

		let detailsEvt = ( response : any ) =>
		{
			if( response?.error )
			{
				this.$_router.navigate(['404.html'], {skipLocationChange: true});
			}
		}

		let paramsEvt = ( params : any ) : any =>
		{
			this.payload.id = params.id;

			return from(this.$_propertySRV.searchAssortByCat() ).pipe( switchMap(propEvt) );
		}

		this.fetchAllXHR = this.$_route.params.pipe( switchMap(paramsEvt) ).toPromise().then(detailsEvt);
	}





	public updateXHR : Promise<object> | null = null;
	public observationId : any;
	public payload : any = { types : [], properties : {} };
	public selectedObserver ?: any;
	public selectedSupervisor ?: any;
	public formErrors : any;
	public propErrors : any;

	public update()
	{
		//
		// include observer and supervisor values
		//
		this.payload.observer = this.selectedObserver?.id;
		this.payload.supervisor = this.selectedSupervisor?.id;

		//
		// make the API request
		//
		let responseEvt = ( response : any ) =>
		{
			this.updateXHR = null;
			this.formErrors = response?.error;
			this.propErrors = this.formErrors?.bbs_observation?.properties;

			let o;

			if( o = response?.result?.bbs_observation )
			{
				this.payload.notes = o?.notes;
				this.payload.recommendation = o?.recommendation;
				this.payload.actionTaken = o?.action_taken;
				this.payload.feedbackToCoworkers = o?.feedback_to_coworkers;

				let observer = o?.observer;

				this.selectedObserver =
				{
					id : observer?.id,
					firstname : observer?.name,
					deleted : false
				}

				let supervisor = o?.supervisor;

				this.selectedSupervisor =
				{
					id : supervisor?.id,
					firstname : supervisor?.name,
					deleted : false
				}

				let properties = ( o?.properties?.length ) ? o.properties : [];

				this.attachmentFile = o?.attachment_file ? this.$_sanitizer.bypassSecurityTrustResourceUrl(o?.attachment_file) : null;

				this._showProperties(properties);
			}

			if( response?.error?.bbs_observation?.id )
			{
				this.$_router.navigate(["404.html"], { skipLocationChange: true });
			}

			return response;
		}

		return this.updateXHR = this.$_bbsObservationSRV.update(this.payload).then(responseEvt);
	}





	public updateComplete : null | number = null;

	public sendChanges()
	{
		this.prevAttachmentFile = null;
		this.updateComplete = null;

		this.update().then( response => this.updateComplete = response?.error ? 0 : 1  );
	}





	public attachmentFile : any ;

	public onFileChange( $event : any )
	{
	    if( $event.target.files.length > 0 )
	    {
	    	if( !this.prevAttachmentFile )
	    	{
	    		this.prevAttachmentFile = this.attachmentFile;
	    	}

	    	let selectedFile = $event.target.files[0];

	    	this.attachmentFile = this.$_sanitizer.bypassSecurityTrustResourceUrl(URL.createObjectURL(selectedFile));
	    	this.payload.attachment_file = selectedFile;
	    }
	}





	public prevAttachmentFile : any;

	public restoreAttachmentFile()
	{
		this.attachmentFile = this.prevAttachmentFile;
		this.prevAttachmentFile = null;
	}

	public removeAttachmentFile()
	{
		if( !this.prevAttachmentFile )
		{
			this.prevAttachmentFile = this.attachmentFile;
		}

		this.attachmentFile = null;
		this.payload.attachment_file = 0;
	}





	public propTypes : any = [];
	public propCategory : any = [];

	private _showProperties( $apiData : Array<any> )
	{
		let appendValueEvt = ( $data : any ) =>
		{
			let filterEvt = ( $property : any ) =>
			{
				if( parseInt($data.id) === $property.id )
				{
					$property.value = $data.value;

					if( $property.value )
					{
						$property.hasRecord = true;
					}
				}
			}

			this.BBSProperties
				.map( category => category?.properties ?? [] )
				.forEach( properties => properties.forEach( filterEvt ) );
		}

		$apiData.forEach(appendValueEvt);

		this.propCategory = this.BBSProperties.filter( category => 5 !== category.id );
		this.propTypes = this.BBSProperties.filter( category => 5 === category.id )[0].properties;
	}



	public setTypeValue( $propId : number, $value : any, $isChecked : boolean )
	{
		this.payload.properties[ $propId ] = { id : $propId, value : $value, deleted : $isChecked ? 0 : 1 };
	}



	public setObservationTypeValue( $propId : number, $value : any, $isChecked : boolean )
	{
		for( let typeProp of this.propTypes )
		{
			//
			// if property has record in database
			// set its value to 0 else remove the value to prevent unnecessary properties sent to the api
			//
			let id = typeProp.id;

			if( typeProp.hasRecord )
			{
				this.setTypeValue( id, 0, $isChecked );
			}
			else
			{
				this.payload.properties[id] = undefined;
			}
		}

		this.setTypeValue( $propId, $value, $isChecked );
	}
}
