import { Component, OnInit } from '@angular/core';
import { OfficeService } from '../office.service';





@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})
export class CreateComponent implements OnInit
{
	constructor( private $_officeSRV : OfficeService ) { }





	ngOnInit(): void
	{
		//
	}




	public payload : any = {};
	public accountId ?: string;
	public formErrors : any;

	public save()
	{
		//
		// make the API request
		//
		let responseHdl = ( response : any ) =>
		{
			this.accountId = undefined;
			this.formErrors = undefined;

			if( response?.error )
			{
				this.formErrors = response.error;
			}

			if( response?.result?.account_id )
			{
				this.accountId = response.result.account_id;
			}
		}

		this.$_officeSRV.create(this.payload).then(responseHdl);
	}
}
