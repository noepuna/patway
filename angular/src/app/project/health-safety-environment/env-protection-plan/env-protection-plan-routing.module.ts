import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EnvProtectionPlanComponent } from './env-protection-plan.component';

const routes: Routes = [{ path: '', component: EnvProtectionPlanComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EnvProtectionPlanRoutingModule { }
