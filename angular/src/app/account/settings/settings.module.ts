import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { SettingsRoutingModule } from './settings-routing.module';
import { MainComponent } from './main/main.component';
import { ChangePasswordPaneComponent } from './change-password-pane/change-password-pane.component';
import { ProfilePhotoPaneComponent } from './profile-photo-pane/profile-photo-pane.component';
import { PersonalDetailsPaneComponent } from './personal-details/personal-details-pane.component';
 
@NgModule({
	declarations:
	[
		MainComponent,
		ProfilePhotoPaneComponent,
		ChangePasswordPaneComponent,
		PersonalDetailsPaneComponent
	],
	imports:
	[
		CommonModule,
		SettingsRoutingModule,
		FormsModule
	]
})
export class SettingsModule { }
