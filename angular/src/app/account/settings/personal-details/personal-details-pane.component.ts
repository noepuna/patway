import { Component, OnInit } from '@angular/core'; 
import { StaffService } from 'src/app/backoffice/staff/staff.service';

@Component({
  selector: 'app-personal-details-pane', 
  templateUrl: './personal-details-pane.component.html',
  styleUrls: ['./personal-details-pane.component.scss']
})
export class PersonalDetailsPaneComponent implements OnInit
{
	constructor( private $_staffSRV : StaffService ) { }

	ngOnInit(): void
	{

	}

    /**
     * no your magic here
     */

}
