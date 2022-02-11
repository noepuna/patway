import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { OfficeRoutingModule } from './office-routing.module';
import { CreateComponent } from './create/create.component';
import { ListComponent } from './list/list.component';
import { UpdateModule } from './update/update.module';


@NgModule({
	declarations:
	[
		CreateComponent,
		ListComponent
	],
	imports:
	[
		CommonModule,
		FormsModule,
		OfficeRoutingModule,
		UpdateModule
	]
})
export class OfficeModule { }
