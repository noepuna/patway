import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { DrugAlcoholComponent } from './drug-alcohol.component';

const routes: Routes = [{ path: '', component: DrugAlcoholComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DrugAlcoholRoutingModule { }
