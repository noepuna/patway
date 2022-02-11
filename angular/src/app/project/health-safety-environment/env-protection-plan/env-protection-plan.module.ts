import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EnvProtectionPlanRoutingModule } from './env-protection-plan-routing.module';
import { EnvProtectionPlanComponent } from './env-protection-plan.component';


@NgModule({
  declarations: [EnvProtectionPlanComponent],
  imports: [
    CommonModule,
    EnvProtectionPlanRoutingModule
  ]
})
export class EnvProtectionPlanModule { }
