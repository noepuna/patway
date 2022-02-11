import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CommissionRoutingModule } from './commission-routing.module';
import { CommissionComponent } from './commission.component';


@NgModule({
  declarations: [CommissionComponent],
  imports: [
    CommonModule,
    CommissionRoutingModule
  ]
})
export class CommissionModule { }
