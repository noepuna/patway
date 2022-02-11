import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { SupportTicketRoutingModule } from './support-ticket-routing.module';
import { DirectiveModule } from '../standard/directive/directive.module';

import { SupportTicketService } from './support-ticket.service';

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
		SupportTicketService
	],
	imports:
	[
		CommonModule,
		FormsModule,
		SupportTicketRoutingModule,
		DirectiveModule
	]
})

export class SupportTicketModule { }
