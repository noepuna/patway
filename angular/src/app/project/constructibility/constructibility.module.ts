import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ConstructibilityRoutingModule } from './constructibility-routing.module';
import { ConstructibilityComponent } from './constructibility.component';


@NgModule({
  declarations: [ConstructibilityComponent],
  imports: [
    CommonModule,
    ConstructibilityRoutingModule
  ]
})
export class ConstructibilityModule { }
