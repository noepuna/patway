import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { DepartmentService } from '../../backoffice/admin/department/department.service';
import { BehaviorBaseSafetyService } from '../behavior-base-safety.service';
import { PropertyService } from '../property/property.service';
import { iObservation } from '../i-observation';

import { forkJoin, merge } from 'rxjs';





@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})

export class CreateComponent implements OnInit
{
	constructor( private $_deptSRV : DepartmentService, private $_bbsObservationSRV : BehaviorBaseSafetyService, private $_propertySRV : PropertyService ) { }





	ngOnInit(): void
	{
		this.resetForm({initOnly:true});
	}





	public payload : any = {};
	public selectedObserver ?: any;
	public selectedSupervisor ?: any;
	public observationId ?: string;
	public formErrors : any;

	public save()
	{
		//
		// include observer and supervisor values
		//
		this.payload.observer = this.selectedObserver?.id;
		this.payload.supervisor = this.selectedSupervisor?.id;

		//
		// include properties
		//
		for( let category of this.propCategory )
		{
			for( let property of category.properties )
			{
				let propId = property.id;

				this.payload.properties[ propId ] = { id : propId, value : property.value };
			}
		}

		//
		// make the API request
		//
		let responseHdl = ( response : any ) =>
		{
			this.observationId = undefined;
			this.formErrors = undefined;

			this.formErrors = response.error;

			if( response?.result?.observation_id )
			{
				this.resetForm();

				this.observationId = response.result.observation_id;
			}
		}

		this.$_bbsObservationSRV.create(this.payload).then(responseHdl);
	}





	public trackObserverType( $id : number, $value : string, $isChecked : boolean )
	{
		this.payload.types[ $id ] = { id : $id, value : $isChecked ? 1 : null };
	}





	public onFileChange( $event : any )
	{
	    if( $event.target.files.length > 0 )
	    {
	    	this.payload.attachment_file = $event.target.files[0];
	    }
	}





	@ViewChild("attachmentFile") attachMentFileEl !: ElementRef;

	public resetForm( $settings : any = {} )
	{
		this.payload = { types : [], properties : {}, attachment_file : null };
		this.formErrors = null;
		this.selectedSupervisor = null;

		let deptEvt = ( response : any ) =>
		{
			this.officeDepartments = response?.result?.data ?? [];

			return response;
		}

		merge( this.$_deptSRV.search({}).then(deptEvt), this._propertiesInit() ).toPromise();

		if( !$settings?.initOnly )
		{
			this.attachMentFileEl.nativeElement.value = null;
		}
	}





	public officeDepartments : any = [];
	public propTypes : any = [];
	public propCategory : any = [];

	private _propertiesInit()
	{
		let propEvt = ( response : any ) =>
		{
			this.propTypes = [];
			this.propCategory = [];

			let typeEntries;
			let otherEntries : any = {};

			if( typeEntries = response[0]?.result?.data )
			{
				for( let entry of typeEntries )
				{
					if( 5 === entry.category )
					{
						this.propTypes.push({ id: entry.id, label: entry.name });
					}
					else
					{
						let catId = entry.category;

						if( !(catId in otherEntries) )
						{
							otherEntries[catId] = [];
						}

						otherEntries[catId].push({ id: entry.id, label: entry.name, value : null });
					}
				}
			}

			let categoryEntries;

			if( categoryEntries = response[1]?.result?.data )
			{
				for( let entry of categoryEntries )
				{
					if( 5 != entry.id )
					{
						this.propCategory.push
						(
							{
								id : entry.id,
								label : entry.name,
								properties : otherEntries[ entry.id ] ?? []
							}
						);
					}
				}
			}

			return response;
		}

		return forkJoin( this.$_propertySRV.search(), this.$_propertySRV.searchCategory() ).toPromise().then( propEvt );
	}
}
