import { Component, OnInit } from '@angular/core';
import { Router } from "@angular/router";





@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit
{

	constructor( private $_router : Router ){}



	private _homePage : string = "/backoffice/super/office";

	ngOnInit(): void
	{
		this.$_router.navigate(["/login"], {queryParams : { previleges : [3], homepage : this._homePage }, skipLocationChange: true});
	}
}
