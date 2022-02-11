import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { SuperRoutingModule } from './super-routing.module';

import { LoginComponent } from './login/login.component';
import { OfficeModule } from './office/office.module';





@NgModule({
	declarations:
	[
		LoginComponent
	],
	imports:
	[
		CommonModule,
		FormsModule,
		SuperRoutingModule,
		OfficeModule
	]
})
export class SuperModule { }
