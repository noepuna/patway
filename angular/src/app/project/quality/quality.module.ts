import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { QualityRoutingModule } from './quality-routing.module';
import { QualityComponent } from './quality.component';


@NgModule({
  declarations: [QualityComponent],
  imports: [
    CommonModule,
    QualityRoutingModule
  ]
})
export class QualityModule { }
