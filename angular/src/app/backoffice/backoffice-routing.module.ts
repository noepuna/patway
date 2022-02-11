import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AppSettings } from '../standard/app-settings';





const routes: Routes =
[
	{
		path: "admin",
		loadChildren: "./admin/admin.module#AdminModule",
	},
	{
		path: "super",
		loadChildren: "./super/super.module#SuperModule",
		data: { pageType: AppSettings.pageType.super }
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports:[RouterModule]
})
export class BackOfficeRoutingModule { }
