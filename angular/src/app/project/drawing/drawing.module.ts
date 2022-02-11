import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DrawingRoutingModule } from './drawing-routing.module';
import { DrawingComponent } from './drawing.component';


@NgModule({
  declarations: [DrawingComponent],
  imports: [
    CommonModule,
    DrawingRoutingModule
  ]
})
export class DrawingModule { }
