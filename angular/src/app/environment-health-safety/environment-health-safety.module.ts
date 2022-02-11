import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { EnvironmentHealthSafetyRoutingModule } from './environment-health-safety-routing.module';
import { DirectiveModule } from '../standard/directive/directive.module';
import { AppCommonModule } from '../app-common/app-common.module';
import { CustomComponentModule } from '../backoffice/custom-component/custom-component.module';
import { CustomMessagingComponentModule } from '../messaging/custom-component/custom-messaging-component.module';

import { EnvironmentHealthSafetyService } from './environment-health-safety.service';

import { CreateComponent } from './create/create.component';
import { ViewComponent } from './view/view.component';
import { ListComponent } from './list/list.component';
import { RequirementsListComponent } from './requirements-list/requirements-list.component';
import { RequirementPreviewComponent } from './requirement-preview/requirement-preview.component';
import { UpdateComponent } from './update/update.component';






@NgModule({
	declarations:
	[
		CreateComponent,
		ViewComponent,
		ListComponent,
		RequirementsListComponent,
		RequirementPreviewComponent,
		UpdateComponent
	],
	providers:
	[
		EnvironmentHealthSafetyService
	],
	imports:
	[
		CommonModule,
		FormsModule,
		AppCommonModule,
		CustomComponentModule,
		CustomMessagingComponentModule,
		EnvironmentHealthSafetyRoutingModule,
		DirectiveModule
	]
})
export class EnvironmentHealthSafetyModule { }
