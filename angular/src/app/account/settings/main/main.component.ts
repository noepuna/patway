import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
 
@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.scss']
})
export class MainComponent implements OnInit
{
	public selectedTab ?: string;

	public tabNames =
	[
		"password",
		"profile-photo",
		"personal-details",
	];

	constructor( private $_route : ActivatedRoute ) { }

	ngOnInit(): void
	{
		let queryParamsEvt = ( params : any ) =>
		{
			this.selectedTab = params?.tab;
		}

		this.$_route.params.subscribe(queryParamsEvt);
	}
}
