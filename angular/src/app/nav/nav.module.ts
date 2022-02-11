import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { NavRoutingModule } from './nav-routing.module';

import { NavHeaderComponent } from './nav-header/nav-header.component';
import { DefaultHeaderNavComponent } from './default-header-nav/default-header-nav.component';
import { AdminHeaderNavComponent } from './admin-header-nav/admin-header-nav.component';
import { LogoutBtnComponent } from './logout-btn/logout-btn.component';


@NgModule({
	declarations:
	[
		NavHeaderComponent,
		DefaultHeaderNavComponent,
		AdminHeaderNavComponent,
		LogoutBtnComponent
	],
	imports:
	[
		CommonModule,
		NavRoutingModule
	],
	exports:
	[
		NavHeaderComponent
	]
})
export class NavModule { }
