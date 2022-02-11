import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { StaffRoutingModule } from './staff-routing.module';

import { StaffService } from '../../staff/staff.service';

import { CreateComponent } from './create/create.component';
import { ListComponent } from './list/list.component';
import { UpdateStaffModule } from './update/update-staff.module';
import { ViewComponent } from './view/view.component';


@NgModule({
	declarations:
	[
		CreateComponent,
		ListComponent,
		ViewComponent
	],
	providers:
	[
		StaffService
	],
	imports:
	[
		CommonModule,
		FormsModule,
		StaffRoutingModule,
		UpdateStaffModule
	]
})
export class StaffModule { }
