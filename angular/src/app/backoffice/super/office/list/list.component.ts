import { Component, OnInit } from '@angular/core';
import { OfficeService } from '../office.service';





@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit
{

	constructor( private $_officeSRV : OfficeService ) { }

	ngOnInit(): void
	{
		this.search();
	}





	public searchResult : any = { data : [] };
	public searchXHR ?: Promise<object>;


	public search()
	{
		let responseHdl = ( response : any ) =>
		{
			if( response?.result?.data )
			{
				this.searchResult.data = response.result.data;
			}
		}

		this.$_officeSRV.search({}).then(responseHdl);
	}

}
