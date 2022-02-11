import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { EnvDesignCriteriaRoutingModule } from './env-design-criteria-routing.module';
import { EnvDesignCriteriaComponent } from './env-design-criteria.component';


@NgModule({
  declarations: [EnvDesignCriteriaComponent],
  imports: [
    CommonModule,
    EnvDesignCriteriaRoutingModule
  ]
})
export class EnvDesignCriteriaModule { }
