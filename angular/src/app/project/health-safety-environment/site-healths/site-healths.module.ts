import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SiteHealthsRoutingModule } from './site-healths-routing.module';
import { SiteHealthsComponent } from './site-healths.component';


@NgModule({
  declarations: [SiteHealthsComponent],
  imports: [
    CommonModule,
    SiteHealthsRoutingModule
  ]
})
export class SiteHealthsModule { }
