import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CrisisManagementComponent } from './crisis-management.component';

const routes: Routes = [{ path: '', component: CrisisManagementComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CrisisManagementRoutingModule { }
