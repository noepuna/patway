import { Injectable } from '@angular/core';

import { environment } from '../../../../environments/environment'
import { HttpClientService } from '../../../app-common/service/http-client.service';





@Injectable({
  providedIn: 'root'
})
export class DepartmentService
{
	public readonly _API_BASE_URL : string = environment._API_URL_OFFICE + "department/";

	constructor( private $_http: HttpClientService )
	{

	}





	public create( $payload : any )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded",
			},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "create.php", { param: $payload }, httpOptions );
	}





	public update( $payload : any )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded",
			},
			withCredentials : true
		};

		let ofcDept = $payload?.office_department;

		let payload =
		{
			office_department:
			{
				id: ofcDept?.id,
				name: ofcDept?.name,
				description: ofcDept?.description,
				enabled : undefined === ofcDept?.enabled ? null : ( ofcDept.enabled ) ? 1 : 0
			}
		}

		return this.$_http.post( this._API_BASE_URL + "update.php", { param: payload }, httpOptions );
	}





	public search( $payload : any )
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
}
