import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { DepartmentService } from '../department.service';





@Component({
  selector: 'app-update',
  templateUrl: './update.component.html',
  styleUrls: ['./update.component.scss']
})
export class UpdateComponent implements OnInit
{
	private _deptId : any;

	constructor( private $_route : ActivatedRoute, private $_router : Router, private $_deptSRV : DepartmentService ) { }





	ngOnInit(): void
	{
		let DeptNotFoundEvt = ( response : any ) =>
		{
			response?.error?.office_department?.id && this.$_router.navigate(["404.html"], {skipLocationChange: true});

			return response;
		}

		let searchParamEvt = ( params : any ) =>
		{
			this._deptId = params?.id;

			let payload = { office_department : { id : this._deptId } };

			this.initXHR = this.$_deptSRV.update(payload).then(this._responseEvt).then(DeptNotFoundEvt);
		}

		this.$_route.params.subscribe(searchParamEvt);
	}





	public initXHR : any;
	public iDepartment : any = {};
	public formErrors : any;

	private _responseEvt = ( response : any ) =>
	{
		let data;

		if( data = response?.result?.office_department )
		{
			this.iDepartment =
			{
				name: data?.name,
				description: data?.description,
				enabled : data?.enabled
			}
		}

		return response;
	}





	public updateFlag : any;

	public update()
	{
		this.formErrors = null;

		let saveEvt = ( response : any ) =>
		{
			!response?.error && (this.updateFlag = true);

			this.formErrors = response?.error;

			return response;
		}

		let payload : any =
		{
			office_department :
			{
				id: this._deptId,
				name : this.iDepartment.name,
				description: this.iDepartment.description,
				enabled: this.iDepartment.enabled
			}
		}

		this.initXHR = this.$_deptSRV.update(payload).then(this._responseEvt).then(saveEvt);
	}
}
