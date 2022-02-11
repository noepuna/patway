import { Component, OnInit } from '@angular/core';
import { DepartmentService } from '../department.service';





@Component({
  selector: 'app-create',
  templateUrl: './create.component.html',
  styleUrls: ['./create.component.scss']
})
export class CreateComponent implements OnInit
{
	constructor( private $_deptSRV : DepartmentService ) { }





	ngOnInit(): void
	{

	}





	public createXHR : any = null;
	public departmentId : any = null;
	public payload : any = {};
	public formErrors : any;

	public save()
	{
		this.createXHR = null;
		this.departmentId = undefined;

		let responseEvt = ( response : any ) =>
		{
			this.formErrors = response?.error;

			if( this.departmentId = response?.result?.office_department_id )
			{
				this.resetForm();
			}
		}

		this.createXHR = this.$_deptSRV.create({"office_department" : this.payload}).then(responseEvt);
	}





	public resetForm()
	{
		this.payload = {};
		this.formErrors = null;
	}
}
