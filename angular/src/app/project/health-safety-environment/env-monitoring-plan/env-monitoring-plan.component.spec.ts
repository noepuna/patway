import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EnvMonitoringPlanComponent } from './env-monitoring-plan.component';

describe('EnvMonitoringPlanComponent', () => {
  let component: EnvMonitoringPlanComponent;
  let fixture: ComponentFixture<EnvMonitoringPlanComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EnvMonitoringPlanComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EnvMonitoringPlanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
