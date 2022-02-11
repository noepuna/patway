import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { GreenhouseGasRoutingModule } from './greenhouse-gas-routing.module';
import { GreenhouseGasComponent } from './greenhouse-gas.component';


@NgModule({
  declarations: [GreenhouseGasComponent],
  imports: [
    CommonModule,
    GreenhouseGasRoutingModule
  ]
})
export class GreenhouseGasModule { }
