import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { BackOfficeRoutingModule } from './backoffice-routing.module';
import { SuperModule } from './super/super.module';
import { AdminModule } from './admin/admin.module';





@NgModule({
	declarations: [],
	imports:
	[
		CommonModule,
		BackOfficeRoutingModule,
		SuperModule,
		AdminModule
	]
})
export class BackOfficeModule { }
