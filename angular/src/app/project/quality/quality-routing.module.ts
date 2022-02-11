import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { QualityComponent } from './quality.component';

const routes: Routes = [{ path: '', component: QualityComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class QualityRoutingModule { }
