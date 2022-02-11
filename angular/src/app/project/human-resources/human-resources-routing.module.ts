import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HumanResourcesComponent } from './human-resources.component';

const routes: Routes = [{ path: '', component: HumanResourcesComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HumanResourcesRoutingModule { }
