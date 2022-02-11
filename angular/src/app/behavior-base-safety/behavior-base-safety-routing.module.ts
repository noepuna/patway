import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CreateComponent } from './create/create.component';
import { UpdateComponent } from './update/update.component';
import { ViewComponent } from './view/view.component';
import { ListComponent } from './list/list.component';
import { StatusComponent } from './status/status.component';
import { TrendingModule } from './trending/trending.module';





const routes: Routes =
[
	{
		path: "trending",
		loadChildren: "./trending/trending.module#TrendingModule"
	},
	{
		path: "create",
		component: CreateComponent
	},
	{
		path: "update/:id",
		component: UpdateComponent
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
		path: "status",
		component: StatusComponent
	},
	{
		path: "",
		redirectTo: "/behavior-base-safety/list",
		pathMatch: "full"
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})

export class BehaviorBaseSafetyRoutingModule { }
