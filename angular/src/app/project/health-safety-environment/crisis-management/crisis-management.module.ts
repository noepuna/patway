import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CrisisManagementRoutingModule } from './crisis-management-routing.module';
import { CrisisManagementComponent } from './crisis-management.component';


@NgModule({
  declarations: [CrisisManagementComponent],
  imports: [
    CommonModule,
    CrisisManagementRoutingModule
  ]
})
export class CrisisManagementModule { }
