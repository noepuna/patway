import { Injectable } from '@angular/core';

import { environment } from '../../environments/environment'
import { HttpClientService } from '../app-common/service/http-client.service';
import { iObservation } from './i-observation';
import { iSearchRequest } from '../api-patway';





@Injectable({
  providedIn: 'root'
})
export class BehaviorBaseSafetyService
{
	public readonly _API_BASE_URL : string = environment._API_URL_BBS_OBSERVATION;

	constructor( private $_http: HttpClientService )
	{
		
	}





	public create( $payload : any )
	{
		let httpOptions = 
		{
			headers : {},
			withCredentials : true
		};

		let payload = { param : { bbs_observation: {} } };

		payload.param.bbs_observation =
		{
			types : $payload.types,
			properties : $payload.properties,
			observer : $payload.observer,
			supervisor : $payload.supervisor,
			notes : $payload.notes,
			recommendation : $payload.recommendation,
			action_taken : $payload.actionTaken,
			feedback_to_coworkers : $payload.feedbackToCoworkers,
			attachment_file : $payload.attachment_file
		};

		return this.$_http.upload( this._API_BASE_URL + "create.php", payload, httpOptions );
	}





	public update( $payload : any )
	{
		let httpOptions =
		{
			headers : {},
			withCredentials : true
		}

		let payload = { param : { bbs_observation: {} } };

		payload.param.bbs_observation =
		{
			id : $payload.id,
			types : $payload.types,
			properties : $payload.properties,
			observer : $payload.observer,
			supervisor : $payload.supervisor,
			notes : $payload.notes,
			recommendation : $payload.recommendation,
			action_taken : $payload.actionTaken,
			feedback_to_coworkers : $payload.feedbackToCoworkers,
			attachment_file : $payload.attachment_file
		}

		return this.$_http.upload( this._API_BASE_URL + "update.php", payload, httpOptions );
	}





	public view( $payload : { id : string } )
	{
		let httpOptions = 
		{
			headers : { 'Content-Type': "application/x-www-form-urlencoded" },
			withCredentials : true
		};

		let payload = { param : { bbs_observation: $payload } };

		return this.$_http.post( this._API_BASE_URL + "details.php", payload, httpOptions );
	}





	public search( $payload : iSearchRequest = {} )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded"
			},
			withCredentials : true
		};

		let payload =
		{

		};

		return this.$_http.post( this._API_BASE_URL + "search.php", payload, httpOptions );
	}





	public searchPropertyUsage( $payload : iSearchRequest = {} )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded"
			},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "search-property-usage.php", $payload, httpOptions );
	}
}
