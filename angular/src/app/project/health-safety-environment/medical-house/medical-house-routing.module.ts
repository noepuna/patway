import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { MedicalHouseComponent } from './medical-house.component';

const routes: Routes = [{ path: '', component: MedicalHouseComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MedicalHouseRoutingModule { }
