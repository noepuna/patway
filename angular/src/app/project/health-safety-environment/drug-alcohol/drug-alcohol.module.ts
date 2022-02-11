import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { DrugAlcoholRoutingModule } from './drug-alcohol-routing.module';
import { DrugAlcoholComponent } from './drug-alcohol.component';


@NgModule({
  declarations: [DrugAlcoholComponent],
  imports: [
    CommonModule,
    DrugAlcoholRoutingModule
  ]
})
export class DrugAlcoholModule { }
