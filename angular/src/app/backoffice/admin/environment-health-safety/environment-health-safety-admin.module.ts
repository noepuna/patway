import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { EnvironmentHealthSafetyAdminRoutingModule } from './environment-health-safety-admin-routing.module';
import { DirectiveModule } from '../../../standard/directive/directive.module';

import { EnvironmentHealthSafetyService } from '../../../environment-health-safety/environment-health-safety.service';

import { CreateComponent } from './create/create.component';
import { ViewComponent } from './view/view.component';
import { UpdateComponent } from './update/update.component';
import { ListComponent } from './list/list.component';





@NgModule({
	declarations:
	[
		CreateComponent,
		ViewComponent,
		UpdateComponent,
		ListComponent
	],
	providers:
	[
		EnvironmentHealthSafetyService
	],
	imports:
	[
		CommonModule,
		FormsModule,
		EnvironmentHealthSafetyAdminRoutingModule,
		DirectiveModule
	]
})
export class EnvironmentHealthSafetyAdminModule { }
