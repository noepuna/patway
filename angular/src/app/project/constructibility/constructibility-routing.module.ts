import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ConstructibilityComponent } from './constructibility.component';

const routes: Routes = [{ path: '', component: ConstructibilityComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ConstructibilityRoutingModule { }
