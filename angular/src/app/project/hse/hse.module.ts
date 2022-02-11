import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { HseRoutingModule } from './hse-routing.module';
import { HseComponent } from './hse.component';


@NgModule({
  declarations: [HseComponent],
  imports: [
    CommonModule,
    HseRoutingModule
  ]
})
export class HseModule { }
