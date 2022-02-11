import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PermitRegisterComponent } from './permit-register.component';

const routes: Routes = [{ path: '', component: PermitRegisterComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PermitRegisterRoutingModule { }
