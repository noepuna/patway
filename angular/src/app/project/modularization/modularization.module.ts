import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ModularizationRoutingModule } from './modularization-routing.module';
import { ModularizationComponent } from './modularization.component';


@NgModule({
  declarations: [ModularizationComponent],
  imports: [
    CommonModule,
    ModularizationRoutingModule
  ]
})
export class ModularizationModule { }
