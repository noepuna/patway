import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { WorkPackagesComponent } from './work-packages.component';

const routes: Routes = [{ path: '', component: WorkPackagesComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class WorkPackagesRoutingModule { }
