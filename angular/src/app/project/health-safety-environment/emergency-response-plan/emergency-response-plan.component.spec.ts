import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EmergencyResponsePlanComponent } from './emergency-response-plan.component';

describe('EmergencyResponsePlanComponent', () => {
  let component: EmergencyResponsePlanComponent;
  let fixture: ComponentFixture<EmergencyResponsePlanComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EmergencyResponsePlanComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EmergencyResponsePlanComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
