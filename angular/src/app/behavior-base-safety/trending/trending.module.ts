import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { TrendingRoutingModule } from './trending-routing.module';

import { TrendingComponent } from './trending.component';
import { PropertyUsageChartComponent } from './property-usage-chart/property-usage-chart.component';





@NgModule({
	declarations:
	[
		TrendingComponent,
		PropertyUsageChartComponent
	],
	imports:
	[
		CommonModule,
		FormsModule,
		TrendingRoutingModule
	],
	exports:
	[
		PropertyUsageChartComponent
	]
})
export class TrendingModule { }
