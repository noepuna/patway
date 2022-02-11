import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.scss']
})

export class ViewComponent implements OnInit
{
	constructor( private $_route : ActivatedRoute, private $_router : Router ) {}

	ngOnInit(): void
	{

	}
	
}
