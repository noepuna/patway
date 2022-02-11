import { Injectable } from '@angular/core';

import { environment } from '../../../environments/environment'
import { HttpClientService } from '../../app-common/service/http-client.service';
import { iSearchRequest } from '../../api-patway';

import { forkJoin } from 'rxjs';





@Injectable({
  providedIn: 'root'
})

export class PropertyService
{
	public readonly _API_BASE_URL : string = environment._API_URL_BBS_OBSERVATION + "property/";

	constructor( private $_http: HttpClientService )
	{

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





	public searchCategory( $payload : iSearchRequest = {} )
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

		return this.$_http.post( this._API_BASE_URL + "category-search.php", payload, httpOptions );
	}





	public searchAssortByCat()
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

		let propEvt = ( response : any ) =>
		{
			let typeEntries;
			let otherEntries : any = {};

			if( typeEntries = response[0]?.result?.data )
			{
				for( let entry of typeEntries )
				{
					let catId = entry.category;

					if( !(catId in otherEntries) )
					{
						otherEntries[catId] = [];
					}

					otherEntries[catId].push({ id: entry.id, label: entry.name });
				}
			}

			let categoryEntries;
			let categorizedProperties = [];

			if( categoryEntries = response[1]?.result?.data )
			{
				for( let entry of categoryEntries )
				{
					categorizedProperties.push
					(
						{
							id : entry.id,
							label : entry.name,
							properties : otherEntries[ entry.id ] ?? []
						}
					);
				}
			}

			return categorizedProperties;
		}

		return forkJoin( this.search(), this.searchCategory() ).toPromise().then( propEvt );
	}
}
