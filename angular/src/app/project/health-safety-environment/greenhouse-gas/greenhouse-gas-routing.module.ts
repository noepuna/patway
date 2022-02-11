import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { GreenhouseGasComponent } from './greenhouse-gas.component';

const routes: Routes = [{ path: '', component: GreenhouseGasComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class GreenhouseGasRoutingModule { }
