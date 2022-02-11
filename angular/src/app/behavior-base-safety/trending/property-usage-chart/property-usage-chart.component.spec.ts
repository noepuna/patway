import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PropertyUsageChartComponent } from './property-usage-chart.component';

describe('PropertyUsageChartComponent', () => {
  let component: PropertyUsageChartComponent;
  let fixture: ComponentFixture<PropertyUsageChartComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PropertyUsageChartComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PropertyUsageChartComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
