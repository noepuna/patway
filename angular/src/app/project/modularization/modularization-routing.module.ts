import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ModularizationComponent } from './modularization.component';

const routes: Routes = [{ path: '', component: ModularizationComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ModularizationRoutingModule { }
