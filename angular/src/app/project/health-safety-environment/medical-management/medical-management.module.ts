import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MedicalManagementRoutingModule } from './medical-management-routing.module';
import { MedicalManagementComponent } from './medical-management.component';


@NgModule({
  declarations: [MedicalManagementComponent],
  imports: [
    CommonModule,
    MedicalManagementRoutingModule
  ]
})
export class MedicalManagementModule { }
