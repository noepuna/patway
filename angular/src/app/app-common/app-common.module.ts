import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { DropdownComponent } from './custom-component/dropdown/dropdown.component';
import { DatePickerComponent } from './custom-component/date-picker/date-picker.component';
//import { TextMorphInputComponent } from './custom-directive/component/text-morph-input/text-morph-input.component';

@NgModule({
	declarations:
	[
		//TextMorphInputComponent
		DropdownComponent,
		DatePickerComponent
	],
	imports:
	[
		CommonModule,
		FormsModule
	],
	exports:
	[
		DropdownComponent,
		DatePickerComponent
	]
})
export class AppCommonModule { }
