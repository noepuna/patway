import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { WasteManagementRoutingModule } from './waste-management-routing.module';
import { WasteManagementComponent } from './waste-management.component';


@NgModule({
  declarations: [WasteManagementComponent],
  imports: [
    CommonModule,
    WasteManagementRoutingModule
  ]
})
export class WasteManagementModule { }
