import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { StaffAccountInputComponent } from './staff-account-input/staff-account-input.component';



@NgModule({
  	declarations:
	[
		StaffAccountInputComponent
	],
	imports:
	[
		CommonModule
	],
	exports:
	[
		StaffAccountInputComponent
	]
})
export class CustomComponentModule { }
