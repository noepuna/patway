import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EmergencyResponsePlanComponent } from './emergency-response-plan.component';

const routes: Routes = [{ path: '', component: EmergencyResponsePlanComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EmergencyResponsePlanRoutingModule { }
