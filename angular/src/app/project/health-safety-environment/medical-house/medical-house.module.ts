import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MedicalHouseRoutingModule } from './medical-house-routing.module';
import { MedicalHouseComponent } from './medical-house.component';


@NgModule({
  declarations: [MedicalHouseComponent],
  imports: [
    CommonModule,
    MedicalHouseRoutingModule
  ]
})
export class MedicalHouseModule { }
