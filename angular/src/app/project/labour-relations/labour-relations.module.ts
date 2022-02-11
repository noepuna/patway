import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { LabourRelationsRoutingModule } from './labour-relations-routing.module';
import { LabourRelationsComponent } from './labour-relations.component';


@NgModule({
  declarations: [LabourRelationsComponent],
  imports: [
    CommonModule,
    LabourRelationsRoutingModule
  ]
})
export class LabourRelationsModule { }
