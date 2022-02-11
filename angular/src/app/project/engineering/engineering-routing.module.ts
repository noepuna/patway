import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EngineeringComponent } from './engineering.component';

const routes: Routes = [{ path: '', component: EngineeringComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EngineeringRoutingModule { }
