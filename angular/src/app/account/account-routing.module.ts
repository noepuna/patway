import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SettingsModule } from './settings/settings.module';





const routes: Routes =
[
	{
		path: "settings",
		loadChildren: "./settings/settings.module#SettingsModule"
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AccountRoutingModule { }
