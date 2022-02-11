import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EarlySafetyRoutingModule } from './early-safety-routing.module';
import { EarlySafetyComponent } from './early-safety.component';


@NgModule({
  declarations: [EarlySafetyComponent],
  imports: [
    CommonModule,
    EarlySafetyRoutingModule
  ]
})
export class EarlySafetyModule { }
