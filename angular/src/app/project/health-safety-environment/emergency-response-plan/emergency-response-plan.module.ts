import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EmergencyResponsePlanRoutingModule } from './emergency-response-plan-routing.module';
import { EmergencyResponsePlanComponent } from './emergency-response-plan.component';


@NgModule({
  declarations: [EmergencyResponsePlanComponent],
  imports: [
    CommonModule,
    EmergencyResponsePlanRoutingModule
  ]
})
export class EmergencyResponsePlanModule { }
