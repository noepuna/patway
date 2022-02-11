import { Component, OnInit, Input } from '@angular/core';
import { AdminService } from '../../../admin.service';





@Component({
  selector: 'app-staff-update-assigned-tasks',
  templateUrl: './assigned-tasks.component.html',
  styleUrls: ['./assigned-tasks.component.scss']
})
export class AssignedTasksComponent implements OnInit
{
	@Input() staffId ?: string;

	constructor( private $_adminSRV : AdminService ) { }





	public initXHR : any;

	private _responseEvt = ( response : any ) =>
	{
		let callbackEvt = function( task : any, index : number )
		{
			let tasks = response.tasks ?? [];

			for( let responseData of tasks )
			{
				let findResult;

				if( task.id === parseInt(responseData?.id) )
				{
					task.isChecked = true;

					return;
				}
			}

			task.isChecked = false;
		}

		this.tasks.forEach( callbackEvt );

		return response;
	}

	ngOnInit(): void
	{
		this.initXHR = this.$_adminSRV.updateTasks({ auth : { id: this.staffId } }).then(this._responseEvt);
	}





	public tasks : any[] =
	[
		{ id: 5,	name: "Event", isChecked : false },
		{ id: 10, 	name: "Behavior Based Safety", isChecked : false },
		{ id: 15, 	name: "Environment Health & Safety", isChecked : false }
	];





	public saveXHR : any;
	public updateFlag : any;
	public formErrors : any;
	public payload : any = {};

	save()
	{
		let saveEvt = ( response : any ) =>
		{
			this.updateFlag = true;

			return response;
		}

		let payload : any =
		{
			auth : { id: this.staffId },
			task : {}
		}

		for( let index in this.tasks )
		{
			let task = this.tasks[index];

			payload.task[index] = { id: task.id, disabled : task.isChecked ? 0 : 1 };
		}

		this.updateFlag = false;
		this.saveXHR = this.$_adminSRV.updateTasks(payload).then(this._responseEvt).then(saveEvt);
	}
}