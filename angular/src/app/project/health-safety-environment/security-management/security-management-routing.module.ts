import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SecurityManagementComponent } from './security-management.component';

const routes: Routes = [{ path: '', component: SecurityManagementComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SecurityManagementRoutingModule { }
