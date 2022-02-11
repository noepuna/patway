import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { UpdateStaffRoutingModule } from './update-staff-routing.module';
import { UpdateComponent } from './update.component';
import { AssignedTasksComponent } from './assigned-tasks/assigned-tasks.component';
import { ProfileComponent } from './profile/profile.component';





@NgModule({
	declarations:
	[
		UpdateComponent,
		AssignedTasksComponent,
		ProfileComponent
	],
	imports:
	[
		CommonModule,
		FormsModule,
		UpdateStaffRoutingModule
	]
})
export class UpdateStaffModule { }
