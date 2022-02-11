import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CreateComponent } from './create/create.component';
import { ViewComponent } from './view/view.component';
import { UpdateComponent } from './update/update.component';
import { ListComponent } from './list/list.component';





const routes: Routes =
[
	{
		path: "create",
		component: CreateComponent
	},
	{
		path: "view/:id",
		component: ViewComponent
	},
	{
		path: "update/:id",
		component: UpdateComponent
	},
	{
		path: "list",
		component: ListComponent
	},
	{
		path: "",
		redirectTo: "/backoffice/admin/environment-health-safety/list",
		pathMatch: "full"
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EnvironmentHealthSafetyAdminRoutingModule { }
