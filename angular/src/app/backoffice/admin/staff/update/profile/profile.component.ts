import { Component, OnInit, Input } from '@angular/core';
import { DepartmentService } from '../../../department/department.service';
import { AdminService } from '../../../admin.service';

import { forkJoin } from 'rxjs';





@Component({
  selector: 'app-staff-update-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})

export class ProfileComponent implements OnInit
{
	@Input() staffId ?: string;

	constructor( private $_deptSRV : DepartmentService, private $_adminSRV : AdminService ) { }





	public initXHR : any = null;
	public deptEntries : any[] = [];

	ngOnInit(): void
	{
		let initFn = ( response : any ) =>
		{
			let myDepartments = response[1]?.result?.data ?? [];

			let departments = response[0]?.result?.data;

			if( departments && departments?.length )
			{
				for( let department of departments )
				{
					let i = myDepartments.indexOf(department?.id);
					let iEntry = { id: department?.id, name: department?.name, selected : false };

					if( i > -1 )
					{
						iEntry.selected = true;
						this.payload.selectedDepartment = department?.id;
					}

					this.deptEntries.push(iEntry);
				}
			}

			return response;
		}

		this.initXHR = forkJoin( this.$_deptSRV.search({}), this.$_adminSRV.createStaffDepartment({id : this.staffId}) ).toPromise().then(initFn);
	}





	public saveXHR : any;
	public updateFlag : any;
	public formErrors : any;
	public payload : any = { selectedDepartment : null };

	save()
	{
		this.updateFlag = false;

		let saveEvt = ( response : any ) =>
		{
			this.updateFlag = true;

			return response;
		}

		// add the selected department

		let payload : any =
		{
			id : this.staffId,
			department : [ { id: this.payload.selectedDepartment, deleted : 0 } ]
		}

		// remove the previous departments

		for( let department of this.deptEntries )
		{
			if( department.selected && department.id != this.payload.selectedDepartment )
			{
				payload.department.push({ id: department.id, deleted: 1 });
			}
		}

		return this.$_adminSRV.createStaffDepartment(payload).then(saveEvt);
	}
}