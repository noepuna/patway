import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './login/login.component';





const routes: Routes =
[
	{
		path: '',
		redirectTo: 'login',
		pathMatch: 'full'
	},
	{
		path: "login",
		component: LoginComponent
	},
	{
		path: "department",
		loadChildren: "./department/department.module#DepartmentModule"
	},
	{
		path: "staff",
		loadChildren: "../../backoffice/admin/staff/staff.module#StaffModule"
	},
	{
		path: "environment-health-safety",
		loadChildren: "./environment-health-safety/environment-health-safety-admin.module#EnvironmentHealthSafetyAdminModule"
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})

export class AdminRoutingModule { }
