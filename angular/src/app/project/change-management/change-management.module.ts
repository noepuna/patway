import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ChangeManagementRoutingModule } from './change-management-routing.module';
import { ChangeManagementComponent } from './change-management.component';


@NgModule({
  declarations: [ChangeManagementComponent],
  imports: [
    CommonModule,
    ChangeManagementRoutingModule
  ]
})
export class ChangeManagementModule { }
