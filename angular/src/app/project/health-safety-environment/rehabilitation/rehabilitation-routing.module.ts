import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RehabilitationComponent } from './rehabilitation.component';

const routes: Routes = [{ path: '', component: RehabilitationComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RehabilitationRoutingModule { }
