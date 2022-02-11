import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CreateComponent } from './create/create.component';
import { ViewComponent } from './view/view.component';
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
		path: "list",
		component: ListComponent
	},
	{
		path: "",
		redirectTo: "/event/list",
		pathMatch: "full"
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})

export class EventRoutingModule { }
