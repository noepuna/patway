import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { RehabilitationRoutingModule } from './rehabilitation-routing.module';
import { RehabilitationComponent } from './rehabilitation.component';


@NgModule({
  declarations: [RehabilitationComponent],
  imports: [
    CommonModule,
    RehabilitationRoutingModule
  ]
})
export class RehabilitationModule { }
