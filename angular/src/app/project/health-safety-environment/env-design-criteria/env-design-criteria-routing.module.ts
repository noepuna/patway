import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EnvDesignCriteriaComponent } from './env-design-criteria.component';

const routes: Routes = [{ path: '', component: EnvDesignCriteriaComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EnvDesignCriteriaRoutingModule { }
