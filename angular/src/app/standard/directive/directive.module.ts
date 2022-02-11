import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { BackNavDirective } from './back-nav.directive';



@NgModule({
	declarations:
	[
		BackNavDirective
	],
	imports:
	[
		CommonModule
	],
	exports:
	[
		BackNavDirective
	]
})
export class DirectiveModule { }
