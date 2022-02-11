import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EnvMonitoringPlanComponent } from './env-monitoring-plan.component';

const routes: Routes = [{ path: '', component: EnvMonitoringPlanComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EnvMonitoringPlanRoutingModule { }
