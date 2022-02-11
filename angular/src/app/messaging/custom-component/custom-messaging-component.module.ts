import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CommentsSectionComponent } from './comments-section/comments-section.component';





@NgModule({
	declarations:
	[
		CommentsSectionComponent
	],
	imports:
	[
		CommonModule,
		FormsModule
	],
	exports:
	[
		CommentsSectionComponent
	]
})
export class CustomMessagingComponentModule { }
