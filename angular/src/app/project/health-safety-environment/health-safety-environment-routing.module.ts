import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HealthSafetyEnvironmentComponent } from './health-safety-environment.component';

const routes: Routes = [{ path: '', component: HealthSafetyEnvironmentComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HealthSafetyEnvironmentRoutingModule { }
