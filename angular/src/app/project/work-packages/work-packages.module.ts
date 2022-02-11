import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { WorkPackagesRoutingModule } from './work-packages-routing.module';
import { WorkPackagesComponent } from './work-packages.component';


@NgModule({
  declarations: [WorkPackagesComponent],
  imports: [
    CommonModule,
    WorkPackagesRoutingModule
  ]
})
export class WorkPackagesModule { }
