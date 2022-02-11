import { Injectable } from '@angular/core';

import { environment } from '../../../environments/environment'
import { HttpClientService } from '../../app-common/service/http-client.service';





@Injectable({
  providedIn: 'root'
})
export class AdminService
{
	public readonly _API_BASE_URL : string = environment._API_URL_OFFICE_ADMIN;

	constructor( private $_http: HttpClientService )
	{
		
	}





	public updateTasks( $payload : any )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded",
			},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "task/update.php", { param: $payload }, httpOptions );
	}





	public createStaffDepartment( $payload : any )
	{
		let httpOptions = 
		{
			headers :
			{
				'Content-Type': "application/x-www-form-urlencoded",
			},
			withCredentials : true
		};

		return this.$_http.post( this._API_BASE_URL + "create-staff-department.php", { param: $payload }, httpOptions );
	}
}
