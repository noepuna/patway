import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LabourRelationsComponent } from './labour-relations.component';

const routes: Routes = [{ path: '', component: LabourRelationsComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LabourRelationsRoutingModule { }
