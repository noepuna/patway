import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { EventRoutingModule } from './event-routing.module';
import { DirectiveModule } from '../standard/directive/directive.module';

import { EventService } from './event.service';

import { CreateComponent } from './create/create.component';
import { ViewComponent } from './view/view.component';
import { ListComponent } from './list/list.component';





@NgModule({
	declarations:
	[
		CreateComponent,
		ViewComponent,
		ListComponent
	],
	providers:
	[
		EventService
	],
	imports:
	[
		CommonModule,
		FormsModule,
		EventRoutingModule,
		DirectiveModule
	]
})

export class EventModule { }
