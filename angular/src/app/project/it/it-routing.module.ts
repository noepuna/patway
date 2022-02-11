import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ItComponent } from './it.component';

const routes: Routes = [{ path: '', component: ItComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ItRoutingModule { }
