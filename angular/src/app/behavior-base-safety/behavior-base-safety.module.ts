import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { BehaviorBaseSafetyRoutingModule } from './behavior-base-safety-routing.module';
import { DirectiveModule } from '../standard/directive/directive.module';
import { CustomComponentModule } from '../backoffice/custom-component/custom-component.module';

import { BehaviorBaseSafetyService } from './behavior-base-safety.service';

import { CreateComponent } from './create/create.component';
import { UpdateComponent } from './update/update.component';
import { ViewComponent } from './view/view.component';
import { ListComponent } from './list/list.component';
import { StatusComponent } from './status/status.component';
import { TrendingModule } from './trending/trending.module'


@NgModule({
	declarations:
	[
		CreateComponent,
		UpdateComponent,
		ViewComponent,
		ListComponent,
		StatusComponent
	],
	providers:
	[
		BehaviorBaseSafetyService
	],
	imports:
	[
		CommonModule,
		FormsModule,
		CustomComponentModule,
		BehaviorBaseSafetyRoutingModule,
		DirectiveModule,
		TrendingModule
	]
})
export class BehaviorBaseSafetyModule { }
