import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';





@Component({
  selector: 'app-update',
  templateUrl: './update.component.html',
  styleUrls: ['./update.component.scss']
})
export class UpdateComponent implements OnInit
{
	public accountId ?: string;
	public selectedSegment ?: string;

	public segmentCol =
	[
		"profile",
		"Assigned Tasks",
		"security"
	];

	constructor( private $_route : ActivatedRoute ) { }

	ngOnInit(): void
	{
		this.$_route.params.subscribe( ( params : any ) => this.accountId = params.accountId );

		let queryParamsEvt = ( queryParams : any ) =>
		{
			this.selectedSegment = queryParams?.segment;
		}
		
		this.$_route.queryParams.subscribe(queryParamsEvt);
	}
}
