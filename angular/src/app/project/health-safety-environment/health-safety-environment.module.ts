import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { HealthSafetyEnvironmentRoutingModule } from './health-safety-environment-routing.module';
import { HealthSafetyEnvironmentComponent } from './health-safety-environment.component';


@NgModule({
  declarations: [HealthSafetyEnvironmentComponent],
  imports: [
    CommonModule,
    HealthSafetyEnvironmentRoutingModule
  ]
})
export class HealthSafetyEnvironmentModule { }
