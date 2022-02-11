import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { DepartmentRoutingModule } from './department-routing.module';
import { CreateComponent } from './create/create.component';
import { SearchComponent } from './search/search.component';
import { UpdateComponent } from './update/update.component';





@NgModule({
	declarations:
	[
		CreateComponent,
		SearchComponent,
		UpdateComponent
	],
	imports:
	[
		CommonModule,
		FormsModule,
		DepartmentRoutingModule
	]
})
export class DepartmentModule { }
