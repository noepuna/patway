import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EarlySafetyComponent } from './early-safety.component';

const routes: Routes = [{ path: '', component: EarlySafetyComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EarlySafetyRoutingModule { }
