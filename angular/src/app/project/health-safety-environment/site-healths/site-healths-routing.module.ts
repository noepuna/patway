import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SiteHealthsComponent } from './site-healths.component';

const routes: Routes = [{ path: '', component: SiteHealthsComponent }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SiteHealthsRoutingModule { }
