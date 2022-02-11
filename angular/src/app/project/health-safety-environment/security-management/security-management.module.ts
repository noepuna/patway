import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SecurityManagementRoutingModule } from './security-management-routing.module';
import { SecurityManagementComponent } from './security-management.component';


@NgModule({
  declarations: [SecurityManagementComponent],
  imports: [
    CommonModule,
    SecurityManagementRoutingModule
  ]
})
export class SecurityManagementModule { }
