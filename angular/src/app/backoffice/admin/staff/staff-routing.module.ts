import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CreateComponent } from './create/create.component';
import { UpdateComponent } from './update/update.component';
import { ListComponent } from './list/list.component';
import { ViewComponent } from './view/view.component';





const routes: Routes =
[
	{
		path: '',
		redirectTo: "list",
		pathMatch: "full"
	},
	{
		path: "create",
		component: CreateComponent
	},
	{
		path: "update",
		loadChildren: "./update/update-staff.module#UpdateStaffModule"
	},
	{
		path: "list",
		component: ListComponent
	},
	{
		path: "view",
		component: ViewComponent
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})

export class StaffRoutingModule { }
