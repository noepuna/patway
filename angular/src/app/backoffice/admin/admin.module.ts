import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { AdminRoutingModule } from './admin-routing.module';

import { DepartmentModule } from './department/department.module';
import { StaffModule } from './staff/staff.module';
import { LoginComponent } from './login/login.component';
import { EnvironmentHealthSafetyAdminModule } from './environment-health-safety/environment-health-safety-admin.module';





@NgModule({
	declarations:
	[
		LoginComponent
	],
	imports:
	[
		CommonModule,
		FormsModule,
		AdminRoutingModule,
		DepartmentModule,
		StaffModule,
		EnvironmentHealthSafetyAdminModule,
	]
})
export class AdminModule { }
