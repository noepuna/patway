import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { WasteManagementComponent } from './waste-management.component';

const routes: Routes = [{ path: '', component: WasteManagementComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class WasteManagementRoutingModule { }
