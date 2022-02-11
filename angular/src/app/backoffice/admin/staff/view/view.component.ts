import { Component, OnInit } from '@angular/core';
import { StaffService } from '../../../staff/staff.service';
import { iStaff } from '../../../staff/i-staff';

@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.scss']
})

export class ViewComponent implements OnInit
{
	constructor( private $_staffSRV : StaffService ) { }

	ngOnInit(): void
	{

	}
}
