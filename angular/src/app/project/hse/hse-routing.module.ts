import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HseComponent } from './hse.component';

const routes: Routes = [{ path: '', component: HseComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HseRoutingModule { }
