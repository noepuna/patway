import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EnvMonitoringPlanRoutingModule } from './env-monitoring-plan-routing.module';
import { EnvMonitoringPlanComponent } from './env-monitoring-plan.component';


@NgModule({
  declarations: [EnvMonitoringPlanComponent],
  imports: [
    CommonModule,
    EnvMonitoringPlanRoutingModule
  ]
})
export class EnvMonitoringPlanModule { }
