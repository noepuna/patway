import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PermitRegisterRoutingModule } from './permit-register-routing.module';
import { PermitRegisterComponent } from './permit-register.component';


@NgModule({
  declarations: [PermitRegisterComponent],
  imports: [
    CommonModule,
    PermitRegisterRoutingModule
  ]
})
export class PermitRegisterModule { }
