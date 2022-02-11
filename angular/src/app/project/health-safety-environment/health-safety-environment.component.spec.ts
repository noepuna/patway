import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HealthSafetyEnvironmentComponent } from './health-safety-environment.component';

describe('HealthSafetyEnvironmentComponent', () => {
  let component: HealthSafetyEnvironmentComponent;
  let fixture: ComponentFixture<HealthSafetyEnvironmentComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HealthSafetyEnvironmentComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(HealthSafetyEnvironmentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
