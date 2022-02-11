import { Component, OnInit } from '@angular/core';
import { StaffService } from '../../../staff/staff.service';
import { iStaff } from '../../../staff/i-staff';
import { DepartmentService } from '../../department/department.service';

@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})

export class CreateComponent implements OnInit
{
	constructor( private $_deptSRV : DepartmentService, private $_staffSRV : StaffService ) { }





	public officeDepartments : any = [];

	ngOnInit(): void
	{
		this.resetForm();

		let deptEvt = ( response : any ) =>
		{
			this.officeDepartments = response?.result?.data ?? [];
			return response;
		}

		this.$_deptSRV.search({}).then(deptEvt);
	}





	public payload !: any;
	public staffId ?: string;
	public formErrors : any;

	public save()
	{
		this.payload.auth.username = this.payload?.email;

		let responseHdl = ( response : any ) =>
		{
			this.staffId = undefined;
			this.formErrors = undefined;

			if( response?.error )
			{
				this.formErrors = response.error;
			}

			if( this.staffId = response?.result?.account_id )
			{
				this.resetForm();
			}
		}

		this.$_staffSRV.create(this.payload).then(responseHdl);
	}





	public resetForm()
	{
		this.payload = { auth: {} };
		this.formErrors = null;
	}
}
