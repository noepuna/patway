import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { MainComponent } from './main/main.component';





const routes: Routes =
[
	{
		path : "",
		redirectTo : "password",
		pathMatch : 'full'
	},
	{
		path : ":tab",
		component : MainComponent
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SettingsRoutingModule { }
