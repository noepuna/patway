import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { UpdateModule } from './update/update.module';

import { CreateComponent } from './create/create.component';
import { ListComponent } from './list/list.component';





const routes: Routes =
[
	{
		path: "create",
		component: CreateComponent
	},
	{
		path: "update",
		loadChildren: "./update/update.module#UpdateModule"
	},
	{
		path: "list",
		component: ListComponent
	},
	{
		path: "",
		redirectTo: "list",
		pathMatch: "full"
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class OfficeRoutingModule { }
