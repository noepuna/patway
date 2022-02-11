import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EngineeringRoutingModule } from './engineering-routing.module';
import { EngineeringComponent } from './engineering.component';


@NgModule({
  declarations: [EngineeringComponent],
  imports: [
    CommonModule,
    EngineeringRoutingModule
  ]
})
export class EngineeringModule { }
